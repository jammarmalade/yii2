<?php

namespace backend\controllers;

use Yii;
use backend\models\Tag;
use backend\models\TagSearch;
use yii\web\NotFoundHttpException;
use backend\components\AdminController;

/**
 * TagController implements the CRUD actions for Tag model.
 */
class TagController extends AdminController
{
    /**
     * Lists all Tag models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TagSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tag model.
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
     * Creates a new Tag model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tag();
        $model->setAttribute('uid', Yii::$app->user->id);
        $model->setAttribute('username', Yii::$app->user->identity->username);
        $model->setAttribute('time_create', $this->formatTime );
        $model->setAttribute('time_update', $this->formatTime );
        $model->setAttribute('status', 1);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->save(false)){
                if(Yii::$app->request->post("submitBtn")=='continue'){
                    return $this->redirect(['create']);
                }else{
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tag model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setAttribute('time_update', $this->formatTime );
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tag model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
//        $this->findModel($id)->delete();
        $status = Yii::$app->request->get('status');
        if(!in_array($status,[0,1])){
            return $this->message(['msg' => '数据错误']);
        }
        $this->findModel($id)->updateAll(['status' => $status],['id' => $id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tag model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Tag the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tag::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    //搜索标签
    public function actionSearch(){
        $q = Yii::$app->request->get('term');
        $returnData = Tag::searchTag($q);
        $add = 1;
        $data = [];
        foreach($returnData as $k=>$v){
            $tmp['value'] = $v['id'];
            $tmp['label'] = $v['name'];
            $data[] = $tmp;
            if($v['name']==$q){
                $add = 0;
                break;
            }
        }
        if($add){
            $tmp = [
                'value' => "0",
                'label' => '创建 '.$q.' 标签',
            ];
            array_unshift($data,$tmp);
        }
	echo json_encode($data);
	exit();
    }
}
