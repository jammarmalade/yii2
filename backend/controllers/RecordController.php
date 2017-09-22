<?php

namespace backend\controllers;

use Yii;
use backend\models\Record;
use backend\models\RecordSearch;
use backend\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\Functions as func;
use backend\models\TagRecord;
use backend\models\Tag;

/**
 * RecordController implements the CRUD actions for Record model.
 */
class RecordController extends AdminController
{
    
    /**
     * Lists all Record models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RecordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        //查询id
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Record model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Record model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Record();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Record model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Record model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        //删除对应的标签-记录id
        \backend\models\TagRecord::deleteAll(['rid'=>$id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Record model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Record the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Record::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    //删除记录-标签关系
    public function actionAjaxdeletetag($id) {
        $tagid = Yii::$app->request->get('tagid');
        if(!$tagid){
            return $this->ajaxReturn('','没有tagid',false);
        }
        $res = TagRecord::deleteAll('tid = :tid AND rid=:rid',[':tid'=>$tagid,':rid'=>$id]);
        return $this->ajaxReturn($id,'',true);
    }
    //添加记录-标签关系
    public function actionAddrelation(){
        $rid = Yii::$app->request->post('rid');
        if(!$rid){
            return $this->ajaxReturn('', '缺少 rid', false);
        }
        $tagid = Yii::$app->request->post('tagid');
        $tagname = Yii::$app->request->post('tagname');
        if($tagid==0){
            //新增标签
            if($info = Tag::findOne(['name'=>$tagname])){
                return $this->ajaxReturn($tagname, '该标签已存在', false);
            }
            $model = new Tag();
            $model->setAttribute('uid', Yii::$app->user->identity->id);
            $model->setAttribute('name', $tagname);
            $model->setAttribute('username', Yii::$app->user->identity->username);
            $model->setAttribute('time_create', $this->formatTime );
            $model->setAttribute('time_update', $this->formatTime );
            $model->setAttribute('status', 1);
            if($model->save(false)){
                $tagid = $model->id;
            }else{
                return $this->ajaxReturn($tagname, '增加标签失败', false);
            }
        }
        //添加关系
        $exists = TagRecord::find()->where('rid = :rid AND tid = :tid', [':rid'=>$rid,':tid'=>$tagid])->all();
        if(!$exists){
            $tagRecordModel = new TagRecord();
            $tagRecordModel->uid = Yii::$app->user->identity->id;
            $tagRecordModel->tid = $tagid;
            $tagRecordModel->rid = $rid;
            $tagRecordModel->create_time = $this->formatTime;
            if($tagRecordModel->save(false)){
                $relationid = $tagRecordModel->id;
                return $this->ajaxReturn($tagid, '', true);
            }else{
                return $this->ajaxReturn('', '添加关系失败', false);
            }
        }else{
            return $this->ajaxReturn('', '已存在该标签关系', false);
        }
    }
    //统计
    public function actionStatistics(){
        $chooseDate = Yii::$app->request->get('date');
        
        //默认获取本月数据
        $uid = Yii::$app->user->identity->id;
        $startMouth = $endMouth = date('m');
        $chooseYear = date('Y');
        if($chooseDate){
            $tmmMouth = explode('-', $chooseDate);
            if(count($tmmMouth)==2){
                //获取月份数据
                $startMouth = $endMouth = $tmmMouth[1];
                $chooseYear = $chooseYear;
            }else{
                $chooseYear = $chooseDate;
                //获取年份数据
                $startMouth = 1;
                $endMouth = 12;
            }
        }
        $startDate = "$chooseYear-$startMouth-01";
        $endDate = date("$chooseYear-$endMouth-t",strtotime("$chooseYear-$endMouth"));
        //记录信息
        $recordData = Record::find()->where("uid=:uid AND type!=0 AND date BETWEEN :startDate AND :endDate")
            ->addParams([':uid'=>$uid,':startDate'=>$startDate,':endDate'=>$endDate])
            ->orderBy('time_create DESC')
            ->asArray()
            ->all();
        
        //记录标签
        $rids = array_column($recordData, 'id');
        $recordGroupData = [];
        if($rids){
            $tagRecordData = TagRecord::find()->where('uid =:uid AND rid IN('.join(',',$rids).')')
                ->addParams([':uid'=>$uid])
                ->asArray()
                ->all();
            //按照记录分组
            $tagids = [];
            foreach($tagRecordData as $k=>$v){
                $tagids[] = $v['tid'];
                $recordTag[$v['rid']][] = $v['tid'];
            }
            //标签名称
            $tagData = Tag::find()->where('id IN('.join(',',  array_unique($tagids)).')')->asArray()->all();
            $tagNameData = [];
            foreach($tagData as $k=>$v){
                $tagNameData[$v['id']] = $v['name'];
            }
            //记录按照日期分组
            foreach($recordData as $k=>$v){
                $tmpTags = [];
                foreach($recordTag[$v['id']] as $tmpTagId){
                    $tmpTags[] = $tagNameData[$tmpTagId];
                }
                $v['tags'] = $tmpTags;
                $recordGroupData[$v['date']][] = $v;
            }
        }
        
        $dateArr = func::rangDate($startDate, $endDate,'Y-m-d');
        $ydataIn = $ydataOut = [];
        $data = [];
        $data['accountOut'] = $data['accountIn'] = 0;
        $data['startDate'] = $dateArr[0];
        $data['endDate'] = $dateArr[count($dateArr)-1];
        foreach($dateArr as $k=>$tmpDate){
            $tmpIn = $tmpOut = [];
            $tmpIn['date'] = $tmpOut['date'] = $tmpDate;
            $tmpIn['y'] = $tmpOut['y'] = 0;
            $tmpIn['tags'] = $tmpOut['tags'] = [];
            if(isset($recordGroupData[$tmpDate])){
                foreach($recordGroupData[$tmpDate] as $dateData){
                    if($dateData['type']==1){
                        $data['accountOut'] += $dateData['account'];
                        $tmpOut['y'] += $dateData['account'];
                        $tmpOut['tags'] = array_values(array_unique(array_merge($dateData['tags'], $tmpOut['tags'])));
                    }else{
                        $data['accountIn'] += $dateData['account'];
                        $tmpIn['y'] += $dateData['account'];
                        $tmpIn['tags'] = array_values(array_unique(array_merge($dateData['tags'], $tmpIn['tags'])));
                    }
                }
            }
            $ydataIn[] = $tmpIn;
            $ydataOut[] = $tmpOut;
            $dateArr[$k] = date('n-d',strtotime($tmpDate));
        }
        $data['income'] = $data['accountIn'] - $data['accountOut'];
        
        //所有年份
        $yearArr = range(2017, date('Y'));
        if($startMouth == $endMouth){
            $chooseMouth = $startMouth;
        }
        foreach($recordGroupData as $k=>$v){
            $recordGroupData[$k] = func::multi_array_sort($v, 'account',SORT_DESC);
        }
        
        
//        func::fput($recordGroupData,1);
        return $this->render('statistics', [
            'ydataIn' => $ydataIn,
            'ydataOut' => $ydataOut,
            'dateArr' => $dateArr,
            'recordGroupData' => $recordGroupData,
            'data' => $data,
            'yearArr' => $yearArr,
            'chooseYear' => $chooseYear,
            'chooseMouth' => $chooseMouth,
        ]);
    }
}
