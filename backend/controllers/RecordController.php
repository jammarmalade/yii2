<?php

namespace backend\controllers;

use Yii;
use backend\models\Record;
use backend\models\RecordSearch;
use backend\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\Functions as func;

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
        
        $searchModel->getTag(7);
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
}
