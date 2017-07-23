<?php

namespace api\modules\v1\controllers;

use api\controllers\ApiactiveController;
use api\common\Functions;
use backend\models\Tag;

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
        
        if(Tag::findOne(['name'=>$name])){
            $this->resultError('已存在该标签');
        }
        
        $model = new Tag();
        $model->setAttribute('uid', $this->uid);
        $model->setAttribute('name', $name);
        $model->setAttribute('username', $this->username);
        $model->setAttribute('time_create', $this->formatTime );
        $model->setAttribute('time_update', $this->formatTime );
        $model->setAttribute('status', 1);
        if($model->save(false)){
            $tid = $model->id;
            return $this->result($tid);
        }else{
            $this->resultError('新增失败');
        }
    }
}