<?php

namespace api\modules\v1\controllers;

use api\controllers\ApiactiveController;
use backend\components\Functions as helper;
use backend\models\Tag;
use backend\models\TagRecord;

class TagController extends ApiactiveController
{
    /**
     * 获取标签
     * 192.168.31.200/advanced/api/web/index.php/v1/tag/taglist
     * $q
     */
    public function actionTaglist(){
        $this->isLogin();
        $q = $this->input('post.q', '',1);

        //获取列表
        $returnData = Tag::searchTag($q);
        $add = 0;
        foreach($returnData as $k=>$v){
            if($v['name']!=$q){
                $add = 1;
                break;
            }
        }
        if($add){
            $returnData[] = [
                'id' => "0",
                'name' => $q,
            ];
        }
        return $this->result($returnData);
    }
    /**
     * 增加标签
     * 192.168.31.200/advanced/api/web/index.php/v1/tag/addtag
     * $name
     */
    public function actionAddtag(){
        $this->isLogin();
        $name = $this->input('post.name', '',1);

        if($info = Tag::findOne(['name'=>$name])){
            $res['id'] = $info->id;
            $res['name'] = $info->name;
            return $this->result([$res],'该标签已存在');
        }

        $model = new Tag();
        $model->setAttribute('uid', $this->uid);
        $model->setAttribute('name', $name);
        $model->setAttribute('username', $this->username);
        $model->setAttribute('time_create', $this->formatTime );
        $model->setAttribute('time_update', $this->formatTime );
        $model->setAttribute('status', 1);
        if($model->save(false)){
            $res['id'] = $model->id;
            $res['name'] = $model->name;
            return $this->result([$res]);
        }else{
            $this->resultError('新增失败');
        }
    }
    /**
     * 获取推荐标签
     * 192.168.31.200/advanced/api/web/index.php/v1/tag/recommend
     */
    public function actionRecommend(){
        $this->isLogin(false);
        $tagIdList = [];
        //若是登录
        if($this->uid){
            $limit = 30;
            $start = ($this->input('page', 1) - 1) * $limit;
            //查询最近使用的标签
            $lastUseTag = TagRecord::find()->where('uid = '.$this->uid)->select('tid')->offset($start)->limit($limit)->asArray()->orderBy('create_time DESC')->all();
            $tagIdList = array_column($lastUseTag, 'tid');
        }
//        $tmpCount = 40 - count($tagIdList);//推荐40个
//        if($tmpCount > 0){
//            //查询系统推荐标签
//            $tmpList = Tag::getRecommendTag($tmpCount);
//            $tagIdList = array_merge($tagIdList, $tmpList);
//        }
//        $tagIdList = array_values(array_unique($tagIdList));
        //查询标签
        $tagList = [];
        if($tagIdList){
            $resTagList = Tag::find()->where(['in','id' , $tagIdList])->select('id,name,img')->asArray()->all();
            foreach ($tagIdList as $tmpid) {
                foreach ($resTagList as $v) {
                    if ($v['id'] == $tmpid) {
                        $tagList[] = $v;
                    }
                }
            }
        }
        return $this->result($tagList);
    }
}