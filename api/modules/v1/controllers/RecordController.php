<?php
/**
 * 用户记录操作
 */
namespace api\modules\v1\controllers;

use api\controllers\ApiactiveController;
use backend\components\Functions as helper;
use backend\models\Record;
use backend\models\TagRecord;
use backend\models\Tag;

class RecordController extends ApiactiveController
{
    /**
     * 添加记录
     * $tids  1,2,3,4
     * $account $type $content $imgstatus 
     * $longitude $latitude $weather $remark
     */
    public function actionAdd(){
        $this->isLogin();
        $tagIds = $this->input('post.tids', '',1);
        $tagArr = explode(',', $tagIds);
        if(count($tagArr)>5){
            $this->resultError('只能添加五个标签哦');
        }
        $account = $this->input('post.account', 0);
        if($account < 0){
            $this->resultError('记录金额不能为负数');
        }
        $type = $this->input('post.type', 0);
        $model = new Record();
        if(!in_array($type, array_keys($model->recordType()))){
            $this->resultError('记录金额不能为负数');
        }
        $content = $this->input('post.content');
        //插入记录
        $model->uid = $this->uid;
        $model->username = $this->username;
        $model->account = $account;
        $model->type = $type;
        $model->content = $content;
        $model->imgstatus = 0;
        $model->longitude = $this->input('post.longitude', 0);
        $model->latitude = $this->input('post.latitude', 0);
        $model->weather = '';
        $model->date = $this->input('post.date', date('Y-m-d',$this->timestamp));
        $model->remark = '';
        $model->time_create = $this->formatTime;
        $model->status = 1;
        
        if($model->save(false)){
            $rid = $model->id;
        }else{
            $this->resultError('新增失败');
        }
        //插入关系
        $insertData = [];
        foreach($tagArr as $k=>$v){
            $tmp['uid'] = $this->uid;
            $tmp['tid'] = $v;
            $tmp['rid'] = $rid;
            $tmp['create_time'] = $this->formatTime;
            $insertData[] = $tmp;
        }
        if($insertData){
            \Yii::$app->db->createCommand()->batchInsert(TagRecord::tableName(), ['uid', 'tid', 'rid', 'create_time'], $insertData)->execute();
        }else{
            $this->resultError('没有记录关系');
        }
        return $this->result($rid);
    }
    //记录列表
    public function actionList(){
        $this->isLogin();
        $page = $this->input('post.page',1);
        $limit = 10;
        $startLimit = ($page - 1) * $limit;
        
        $reocrdList = Record::find()->where(['uid'=>$this->uid])->offset($startLimit)->limit($limit)->asArray()->orderBy("time_create DESC")->all();
        $rids = array_column($reocrdList, 'id');
        //查询出标签名称
        $tagRecord = TagRecord::find()->from(TagRecord::tableName().' as tr')
                ->join('LEFT JOIN',Tag::tableName().' as t' , 't.id = tr.tid')
                ->where(['in','rid',$rids])->select('tr.id,tr.rid,tr.tid,t.name as tagname')->asArray()->all();//createCommand()->getRawSql()
        //按照记录分组
        $tagByRecord = [];
        foreach($tagRecord as $k=>$v){
            if($v['tagname']){
                $tmp['id'] = $v['tid'];
                $tmp['name'] = $v['tagname'];
                $tmp['img'] = '';
                $tagByRecord[$v['rid']][] = $tmp;
            }
        }
        foreach($reocrdList as $k=>$v){
            if(isset($tagByRecord[$v['id']])){
                $reocrdList[$k]['tagList'] = $tagByRecord[$v['id']];
                $reocrdList[$k]['showTime'] = substr($v['time_create'],0,16);
            }else{
                unset($reocrdList[$k]);
            }
        }
        return $this->result($reocrdList);
    }
    
}