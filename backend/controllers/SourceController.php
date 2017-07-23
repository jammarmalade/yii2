<?php

namespace backend\controllers;

use Yii;
use backend\models\Source;
use backend\models\SourceSearch;
use backend\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use backend\models\SourceImage;
use backend\components\Functions;

/**
 * SourceController implements the CRUD actions for Source model.
 */
class SourceController extends AdminController {

    /**
     * Lists all Source models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SourceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $psid = [];
        foreach($dataProvider->getModels() as $v){
            $psid[] = $v->id;
        }
        $imageList = SourceImage::find()->select('psid,path')->where(['psid'=>$psid])->groupBy('psid')->indexBy('psid')->all();

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'imageList' => $imageList,
        ]);
    }

    /**
     * Displays a single Source model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $sourceImageList = SourceImage::findAll(['psid' => $id]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'sourceImageList' => $sourceImageList,
        ]);
    }

    /**
     * Creates a new Source model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Source();
        //根据链接获取数据
        $surl = Yii::$app->request->post('Source')['surl'];
        if($surl){
            $host = parse_url($surl)['host'];
            $model->setAttribute('name',$host);
            preg_match('#/(\d+)\.html#', $surl, $m);
            $model->setAttribute('sid',$m[1]);
        }
        $model->setAttribute('time_create', $this->formatTime );
        $model->setAttribute('status', 1);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->save(false)){
                return $this->redirect(['create']);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
//        $model = new Source();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['index']);
//        } else {
//            return $this->render('create', [
//                        'model' => $model,
//            ]);
//        }
    }

    /**
     * 异步校验表单模型
     */
    public function actionValidateForm() {
        $model = new Source();
        $model->load(Yii::$app->request->post());
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return \yii\widgets\ActiveForm::validate($model);
    }

    /**
     * Updates an existing Source model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Url::toRoute('index'));
        } else {
            return $this->renderAjax('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Source model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
//        $this->findModel($id)->delete();
        $status = Yii::$app->request->get('status');
        if(!in_array($status,[0,1,2,3,4,5])){
            return $this->message(['msg' => '数据错误']);
        }
        $this->findModel($id)->updateAll(['status' => $status],['id' => $id]);

        return $this->redirect(['index']);
    }
    public function actionAjaxupdate($id) {
        $status = Yii::$app->request->get('status');
        if(!in_array($status,[0,1,2,3,4,5])){
            return $this->message(['msg' => '数据错误']);
        }
        $this->findModel($id)->updateAll(['status' => $status],['id' => $id]);

        return $this->ajaxReturn($id,'',true);
    }
    public function actionAjaxdigest($id) {
        $digest = Yii::$app->request->get('digest');
        if(!in_array($digest,[0,1])){
            return $this->message(['msg' => '数据错误']);
        }
        $this->findModel($id)->updateAll(['digest' => $digest],['id' => $id]);

        return $this->ajaxReturn($id,'',true);
    }

    /**
     * Finds the Source model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Source the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Source::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
