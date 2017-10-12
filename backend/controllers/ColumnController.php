<?php

namespace backend\controllers;

use Yii;
use common\models\Column;
use backend\models\ColumnSearch;
use backend\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\Functions as tools;

/**
 * ColumnController implements the CRUD actions for Column model.
 */
class ColumnController extends AdminController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Column models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ColumnSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Column model.
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
     * Creates a new Column model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Column();
        
        if (Yii::$app->request->post()) {
            if(!$model->id){
                $model->create_time = $this->formatTime;
            }
            $model->load(Yii::$app->request->post());
            if($model->pid==NULL){
                $model->pid = 0;
            }
            if(!$model->order_number){
                $model->order_number = 0;
            }
            
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Column model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            if($model->pid==NULL || $model->pid==$model->id){
                $model->pid = 0;
            }
            if(!$model->order_number){
                $model->order_number = 0;
            }
            
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Column model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        $status = Yii::$app->request->get('status');
        if(!in_array($status,[1,2])){
            return $this->message(['msg' => '数据错误']);
        }
        $this->findModel($id)->updateAll(['status' => $status],['id' => $id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Column model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Column the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Column::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
