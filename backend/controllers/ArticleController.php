<?php

namespace backend\controllers;

use Yii;
use common\models\Article;
use common\models\ArticleTag;
use backend\models\ArticleSearch;
use backend\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Tag;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends AdminController {

    public function behaviors() {
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
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        
        $type = Yii::$app->request->post('type');
        if ($type=='submit') {
            //执行修改或插入
            return $this->addOrEdit();
        }
        $id = Yii::$app->request->get('id');
        $articleInfo = [];
        if($id){
            $articleInfo = $this->findModel($id);
            //查询标签
            $tagList = ArticleTag::find()->from(ArticleTag::tableName().' as at')
                ->join('LEFT JOIN',Tag::tableName().' as t' , 't.id = at.tid')
                ->where(['at.aid'=>$id])->select('at.id,at.tid,t.name as tagname')->asArray()->all();
        }
        return $this->render('create',[
            'articleInfo' => $articleInfo,
            'tagList' => $tagList,
        ]);
    }
    private function addOrEdit(){
        $aid = Yii::$app->request->post('aid');
        $subject = Yii::$app->request->post('subject');
        if(!trim($subject)){
            return $this->ajaxReturn('', '标题不能为空', false);
        }
        $content = Yii::$app->request->post('content');
        if(!trim($content)){
            return $this->ajaxReturn('', '内容不能为空', false);
        }
        $tagIds = Yii::$app->request->post('tagIds');
        $tagCount = count($tagIds);
        if($tagCount==0){
            return $this->ajaxReturn('', '请添加标签', false);
        }
        if($tagCount>10){
            return $this->ajaxReturn('', '最多添加十个标签', false);
        }
        $imageId = 0;
        $articleModel = new Article();
        $articleTagModel = new ArticleTag();
        //要添加关系的tagid，和要删除关系的tagid
        $saveTagIds = $delTagIds = [];
        $uid = Yii::$app->user->identity->id;
        if(!$aid){
            $articleModel->sid = md5($subject.time());
            $articleModel->uid = $uid;
            $articleModel->username = Yii::$app->user->identity->username;
            $articleModel->subject = htmlspecialchars($subject);
            $articleModel->content = $content;
            $articleModel->view_auth = Yii::$app->request->post('viewAuth');
            $articleModel->image_id = $imageId;
            $articleModel->status = 1;
            $articleModel->time_update = $this->formatTime;
            $articleModel->time_create = $this->formatTime;
            //新增
            if($articleModel->save(false)){
                $aid = $articleModel->id;
            }else{
                return $this->ajaxReturn('', '添加文章失败', false);
            }
            $saveTagIds = $tagIds;
        }else{
            $tagList = ArticleTag::find()->where(['aid'=>$aid])->select('id,tid')->asArray()->all();
            $oldTagIds = array_column($tagList, 'tid');
            $delTagIds = array_diff($oldTagIds,$tagIds);
            $saveTagIds = array_diff($tagIds,$oldTagIds);
        }
        //删除 tag 关系
        if($delTagIds){
            $articleTagModel->deleteAll("aid = :aid AND tid in(:tid)", [':aid'=>$aid,':tid'=>join(',',$delTagIds)]);
        }
        //添加tag关系
        if($saveTagIds){
            $insertData = [];
            foreach($saveTagIds as $k=>$v){
                $tmp = [];
                $tmp['uid'] = $uid;
                $tmp['tid'] = $v;
                $tmp['aid'] = $aid;
                $tmp['create_time'] = $this->formatTime;
                $insertData[] = $tmp;
            }
            Yii::$app->db->createCommand()->batchInsert(ArticleTag::tableName(), ['uid', 'tid', 'aid', 'create_time'], $insertData)->execute();
        }
        
        return $this->ajaxReturn('', '添加失败', false);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
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
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        //$this->findModel($id)->delete();
        $status = Yii::$app->request->get('status');
        if (!in_array($status, [0, 1])) {
            return $this->message(['msg' => '数据错误']);
        }
        $this->findModel($id)->updateAll(['status' => $status], ['id' => $id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
