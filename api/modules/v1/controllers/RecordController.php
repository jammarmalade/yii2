<?php

/**
 * 用户记录操作
 */

namespace api\modules\v1\controllers;

use Yii;
use api\controllers\ApiactiveController;
use backend\components\Functions as helper;
use backend\models\Record;
use backend\models\TagRecord;
use backend\models\Tag;
use backend\models\Image as TableImage;

class RecordController extends ApiactiveController {

    /**
     * 添加记录
     * $tids  1,2,3,4
     * $account $type $content $imgstatus
     * $longitude $latitude $weather $remark
     */
    public function actionAdd() {
        $this->isLogin();
        $tagIds = $this->input('post.tids', '', 1);
        $tagArr = explode(',', $tagIds);
        if (count($tagArr) > 10) {
            $this->resultError('只能添加十个标签哦');
        }
        $type = $this->input('post.type', 0);
        $model = new Record();
        if (!in_array($type, array_keys($model->recordType()))) {
            $this->resultError('类型不正确');
        }
        $account = $this->input('post.account', 0);
        if ($type != 0 && $account < 0) {
            $this->resultError('记录金额不能为负数');
        }
        if($type==0){
            $account = 0;
        }

        $content = $this->input('post.content');
        if($type==0 && $content==''){
            $this->resultError('还是写点记录内容吧~~！');
        }
        $rid = $this->input('post.rid', 0);
        if($rid){
            //修改
            Record::updateAll([
                'type' => $type,
                'account' => $account,
                'content' => $content,
            ], 'id = '.$rid.' AND uid = '.$this->uid);
            //查询出原来的tagid
            $tagRecordList = TagRecord::find()->where(['rid' =>$rid])->asArray()->all();
            $oldTagIds = array_column($tagRecordList, 'tid');
            //需要新增的tagid
            $addTagIds = array_diff($tagArr,$oldTagIds);
            $delTagIds = array_diff($oldTagIds,$tagArr);
            //将删掉的tagid 删除
            if($delTagIds){
                TagRecord::deleteAll(['and',"rid=$rid",['in','tid',  array_values($delTagIds)]]);
            }
            $tagArr = $addTagIds;
        }else{
            //插入记录
            $model->uid = $this->uid;
            $model->username = $this->username;
            $model->account = $account;
            $model->type = $type;
            $model->content = $content;
            $model->imgstatus = 0;
            $model->country = $this->input('post.country', null);
            $model->province = $this->input('post.province', null);
            $model->city = $this->input('post.city', null);
            $model->area = $this->input('post.area', null);
            $model->address = $this->input('post.address', null);
            $model->longitude = $this->input('post.longitude', 0);
            $model->latitude = $this->input('post.latitude', 0);
            $model->weather = '';
            $model->date = $this->input('post.date', date('Y-m-d', $this->timestamp));
            $model->remark = '';
            $model->time_create = $this->formatTime;
            $model->status = 1;

            if ($model->save(false)) {
                $rid = $model->id;
            } else {
                $this->resultError('新增失败');
            }
        }
        //插入关系
        $insertData = [];
        foreach ($tagArr as $k => $v) {
            $tmp['uid'] = $this->uid;
            $tmp['tid'] = $v;
            $tmp['rid'] = $rid;
            $tmp['create_time'] = $this->formatTime;
            $insertData[] = $tmp;
        }
        if ($insertData) {
            \Yii::$app->db->createCommand()->batchInsert(TagRecord::tableName(), ['uid', 'tid', 'rid', 'create_time'], $insertData)->execute();
        } else {
            if(!$rid){
                $this->resultError('没有记录关系');
            }
        }
        //若是有图片数据，保存图片
        if ($_FILES) {
            $imageController = new ImageController($this->id, $this->module);
            if ($imageController->saveImage($rid)) {
                Record::updateAll(['imgstatus' => 1], "id = $rid");
            }
        }
        return $this->result($rid);
    }

    //记录列表
    public function actionList() {
        $this->isLogin();
        $page = $this->input('post.page', 1);
        $limit = $this->input('post.limit', 10);
        $startLimit = ($page - 1) * $limit;

        //是否有标签条件
        $tagName = $this->input('post.tagName', '');
        $tagId = 0;
        if($tagName){
            //查询tagid
            $tagInfo = Tag::find()->where(['name' => $tagName])->select('id,name')->one();
            if($tagInfo){
                $tagId = $tagInfo['id'];
            }
        }
        //是否有日期条件
        $searchDate = $this->input('post.searchDate', '');
        if($searchDate){
            $searchDate = date('Y-m-d',  strtotime($searchDate));
        }
        if($tagId){
            $where['r.uid'] = $this->uid;
            if($searchDate){
                $where['r.date'] = $searchDate;
            }
            $where['tr.tid'] = $tagId;
            $where['r.status'] = 1;
            $query = Record::find()->from(Record::tableName().' r')->innerJoin(['tr' => TagRecord::tableName()], "r.id = tr.rid")->where($where);
            $count = $query->count();
            $reocrdList = $query->offset($startLimit)->limit($limit)->asArray()->orderBy("time_create DESC")->all();
        }else{
            $where['uid'] = $this->uid;
            if($searchDate){
                $where['date'] = $searchDate;
            }
            $where['status'] = 1;
            $count = Record::find()->where($where)->count();
            $reocrdList = Record::find()->where($where)->offset($startLimit)->limit($limit)->asArray()->orderBy("time_create DESC")->all();
        }
        //下一页页数
        $pageCount = ceil($count / $limit);
        $nextPage = 0;
        if($page < $pageCount){
            $nextPage = $page + 1;
        }
        $rids = $sids = [];
        foreach($reocrdList as $k=>$v){
            $rids[] = $v['id'];
            if($v['imgstatus']){
                $sids[] = $v['id'];
            }
        }

        //查询出标签名称
        $tagRecord = TagRecord::find()->from(TagRecord::tableName() . ' as tr')
                        ->join('LEFT JOIN', Tag::tableName() . ' as t', 't.id = tr.tid')
                        ->where(['in', 'rid', $rids])->select('tr.id,tr.rid,tr.tid,t.name as tagname')->asArray()->all(); //createCommand()->getRawSql()
        //按照记录分组
        $tagByRecord = [];
        foreach ($tagRecord as $k => $v) {
            if ($v['tagname']) {
                $tmp['id'] = $v['tid'];
                $tmp['name'] = $v['tagname'];
                $tmp['img'] = '';
                $tagByRecord[$v['rid']][] = $tmp;
            }
        }

        //图片数据
        $imgList = [];
        if($sids){
            $resImgList = TableImage::find()->where(['and',"type=1",['in', 'sid', $sids]])->select('sid,path,thumb')->asArray()->all();
            //根据sid分组
            $imgDomain = Yii::$app->params['imgDomain'];
//            $imgDomain = 'http://192.168.1.136/advanced/uploads/';
            foreach($resImgList as $k=>$v){
                $v['url']  = $v['thumbUrl'] = $imgDomain.$v['path'];
                if($v['thumb']){
                    $v['thumbUrl'] .= '.thumb.jpg';
                }
                unset($v['path']);
                $imgList[$v['sid']][] = $v;
            }
        }
        foreach ($reocrdList as $k => $v) {
            if (isset($tagByRecord[$v['id']])) {
                $reocrdList[$k]['tagList'] = $tagByRecord[$v['id']];
                $reocrdList[$k]['showTime'] = substr($v['time_create'], 0, 16);
                //记录地址
                $reocrdList[$k]['location'] = $v['country'].'·'.($v['province']==$v['city'] ? $v['province'] : $v['province'].'·'.$v['city']).'·'.$v['area'].($v['address'] ? '·'.$v['address'] : '');
                unset($reocrdList[$k]['country'],$reocrdList[$k]['province'],$reocrdList[$k]['city'],$reocrdList[$k]['area'],$reocrdList[$k]['address']);
                //图片数据
                $reocrdList[$k]['imgList'] = [];
                if(isset($imgList[$v['id']])){
                    $reocrdList[$k]['imgList'] = $imgList[$v['id']];
                }
            } else {
                unset($reocrdList[$k]);
            }
        }
        return $this->result($reocrdList,$nextPage);
    }
    /**
     * 获取记录信息
     */
    public function actionInfo(){
        $this->isLogin();
        $rid = $this->input('post.rid', 0);
        if(!$rid){
            $this->resultError('缺少参数 rid');
        }
        $info = Record::find()->where(['id' => $rid,'uid' => $this->uid])->asArray()->one();
        if(!$info){
            $this->resultError('修改记录不存在');
        }
        //查询出标签名称
        $tagRecord = TagRecord::find()->from(TagRecord::tableName() . ' as tr')
                        ->join('LEFT JOIN', Tag::tableName() . ' as t', 't.id = tr.tid')
                        ->where(['rid'=>$rid])->select('tr.id,tr.rid,tr.tid,t.name as tagname')->asArray()->all();
        foreach ($tagRecord as $k => $v) {
            if ($v['tagname']) {
                $tmp['id'] = $v['tid'];
                $tmp['name'] = $v['tagname'];
                $tmp['img'] = '';
                $info['tagList'][] = $tmp;
            }
        }
        $info['showTime'] = substr($info['time_create'], 0, 16);
        return $this->result([$info]);
    }

    /**
     * 转换经纬度为地址信息(自己调用)
     * http://192.168.1.136/advanced/api/web/index.php/v1/record/convertl
     */
    public function actionConvertl() {
        //每次取出100个查询
        $recordList = Record::find()->where(['and', 'country IS NULL', 'longitude > 0'])->limit(100)->asArray()->all();
        $ak = Yii::$app->params['BmapAK'];

        $url = 'http://api.map.baidu.com/geocoder/v2/?output=json&ak=' . $ak . '&location=';
        foreach ($recordList as $k => $v) {
            if ($v['longitude'] > 0 && $v['latitude'] > 0) {
                $tmp = json_decode(file_get_contents($url . $v['latitude'] . ',' . $v['longitude']), 1);
                if ($tmp['status'] == 0 && isset($tmp['result']['addressComponent'])) {
                    $result = $tmp['result']['addressComponent'];
                    Record::updateAll([
                        'country' => $result['country'],
                        'province' => $result['province'],
                        'city' => $result['city'],
                        'area' => $result['district'],
                        'address' => $result['street'] . $result['street_number'],
                    ], 'id = ' . $v['id']);
                }
            }
        }
        return $this->result('');
    }

}
