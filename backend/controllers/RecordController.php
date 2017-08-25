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
}
