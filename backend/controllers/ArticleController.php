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
use backend\components\Functions as tools;
use backend\models\Image;

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
        $articleInfo = $this->findModel($id);
        //替换图片bbcode
        $tmpArr['imgList'] = [];
        if($articleInfo['image_id']){
            $tmpArr = Image::replaceImgCode($articleInfo,'show');
            $articleInfo->content = $tmpArr['content'];
        }
        return $this->render('view', [
            'articleInfo' => $articleInfo,
            'imgList' => $tmpArr['imgList'],
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
        $articleInfo = $tagList = [];
        if($id){
            $articleInfo = $this->findModel($id);
            //替换图片bbcode
            if($articleInfo['image_id']){
                $articleInfo->content = Image::replaceImgCode($articleInfo);
            }
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
        $description = Yii::$app->request->post('description');
        $tagIds = Yii::$app->request->post('tagIds');
        $tagCount = count($tagIds);
        if($tagCount==0){
            return $this->ajaxReturn('', '请添加标签', false);
        }
        if($tagCount>10){
            return $this->ajaxReturn('', '最多添加十个标签', false);
        }
        //提取本站图片，并替换为bbcode
        $content = preg_replace('#<img[^>]+?title="(\d+)\_[a-z0-9]+\.jpg"[^>]*?/>#i','[img]$1[/img]',$content);
        preg_match_all('#\[img\](\d+)\[/img\]#',$content,$m);
        $imageIds = $m[1];
        $imageId = 0;
        //若有图片
        if($imageIds){
            $imageId = $imageIds[0];
        }
        $articleModel = new Article();
        $articleTagModel = new ArticleTag();
        //要添加关系的tagid，和要删除关系的tagid
        $saveTagIds = $delTagIds = [];
        $uid = Yii::$app->user->identity->id;
        if(!$aid){
            $articleModel->subject = htmlspecialchars($subject);
            $articleModel->description = htmlspecialchars($description);
            $articleModel->content = $content;
            $articleModel->view_auth = Yii::$app->request->post('viewAuth');
            $articleModel->image_id = $imageId;
            $articleModel->time_update = $this->formatTime;
        
            $articleModel->sid = md5($subject.time());
            $articleModel->uid = $uid;
            $articleModel->username = Yii::$app->user->identity->username;
            $articleModel->status = 1;
            $articleModel->time_create = $this->formatTime;
            if($articleModel->save(false)){
                $aid = $articleModel->id;
            }else{
                return $this->ajaxReturn('', '添加失败', false);
            }
            $saveTagIds = $tagIds;
        }else{
            $result = $articleModel::find()->where(['id'=>$aid])->one();
            $result->subject = htmlspecialchars($subject);
            $result->description = htmlspecialchars($description);
            $result->content = $content;
            $result->view_auth = Yii::$app->request->post('viewAuth');
            $result->image_id = $imageId;
            $result->time_update = $this->formatTime;
            if(!$result->save()){
                return $this->ajaxReturn('', '修改失败', false);
            }
            $tagList = ArticleTag::find()->where(['aid'=>$aid])->select('id,tid')->asArray()->all();
            $oldTagIds = array_column($tagList, 'tid');
            $delTagIds = array_diff($oldTagIds,$tagIds);
            $saveTagIds = array_diff($tagIds,$oldTagIds);
            //先将本文章的图片全部置为未使用
            Image::updateAll(['sid'=>0,'status'=>2], "sid = :sid AND uid = :uid", [':sid'=>$aid,':uid'=>$uid]);
        }
        //若有图片id ,将图片的sid置为 文章id
        if($imageIds){
            Image::updateAll(['sid'=>$aid,'status'=>1], "uid = $uid AND id IN(".join(',', $imageIds).")");
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
        return $this->ajaxReturn($aid, '操作成功', true);
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
        $updateData = Yii::$app->request->get('status');
        $type = Yii::$app->request->get('type') ? Yii::$app->request->get('type') : 'status';
        if($type == 'status'){
            if (!in_array($updateData, [1, 2])) {
                return $this->message(['msg' => '数据错误']);
            }
            $this->findModel($id)->updateAll(['status' => $updateData], ['id' => $id]);
        }elseif($type == 'recommend'){
            if (!in_array($updateData, [0, 1])) {
                return $this->message(['msg' => '数据错误']);
            }
            $this->findModel($id)->updateAll(['recommend' => $updateData], ['id' => $id]);
        }elseif($type == 'copyright'){
            if (!in_array($updateData, [0, 1])) {
                return $this->message(['msg' => '数据错误']);
            }
            $this->findModel($id)->updateAll(['copyright' => $updateData], ['id' => $id]);
        }

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
