<?php
/**
 * 用户记录操作
 */
namespace api\modules\v1\controllers;

use api\controllers\ApiactiveController;
use api\common\Functions;
use backend\models\Record;
use backend\models\TagRecord;

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
        $model->longitude = 0;
        $model->latitude = 0;
        $model->weather = '';
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
    
}