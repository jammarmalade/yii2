<?php

namespace backend\controllers;

use Yii;
use backend\models\Blog;
use backend\models\BlogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Exception;
use backend\models\BlogCategory;

/**
 * BlogController implements the CRUD actions for Blog model.
 */
class BlogController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            //附加行为
            'as access' => [
                'class' => 'backend\components\AccessControl',
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Blog models.
     * @return mixed
     */
    public function actionIndex() {
        //权限检查
//        if (!Yii::$app->user->can('/blog/index')) {
//            throw new \yii\web\ForbiddenHttpException("没权限访问.");
//        }
        $searchModel = new BlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Blog model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Blog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Blog();
        // 注意这里调用的是validate，非save,save我们放在了事务中处理了
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // 开启事务
            $transaction = Yii::$app->db->beginTransaction();
            try{
                /**
                * current model save
                */
               $model->save(false);
               // 注意我们这里是获取刚刚插入blog表的id
               $blogId = $model->id;
               /**
                * batch insert category
                * 我们在Blog模型中设置过category字段的验证方式是required,因此下面foreach使用之前无需再做判断
                */
               $data = [];
               foreach ($model->category as $k => $v) {
                    // 注意这里的数组形式[blog_id, category_id]，一定要跟下面 batchInsert 方法的第二个参数保持一致
                    $data[] = [$blogId, $v];
                }
                // 获取 BlogCategory 模型的所有属性和表名
                $blogCategory = new BlogCategory;
                $attributes = array_keys($blogCategory->getAttributes());
                $tableName = $blogCategory::tableName();
                // 批量插入栏目到BlogCategory::tableName()表,第一个参数是 BlogCategory 对应的数据表名，
                // 第二个参数是该模型对应的属性字段，第三个参数是我们需要批量插入到该模型的字段，记得第二个参数和第三个参数对应值一致哦
                Yii::$app->db->createCommand()->batchInsert(
                    $tableName, 
                    $attributes,
                    $data
                )->execute();
                // 提交
                $transaction->commit();
                return $this->redirect(['index']);
            } catch (Exception $e) {
                // 回滚
                $transaction->rollback();
                throw $e;
            }
        } else {
            return $this->renderAjax('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Blog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {

                /**
                 * current model save
                 */
                $model->save(false);

                // 注意我们这里是获取刚刚插入blog表的id
                $blogId = $model->id;

                /**
                 * batch insert category
                 * 我们在Blog模型中设置过category字段的验证方式是required,因此下面foreach使用之前无需再做判断
                 */
                $data = [];
                foreach ($model->category as $k => $v) {
                    // 注意这里的属组形式[blog_id, category_id]，一定要跟下面batchInsert方法的第二个参数保持一致
                    $data[] = [$blogId, $v];
                }

                // 获取BlogCategory模型的所有属性和表名
                $blogCategory = new BlogCategory;
                $attributes = array_keys($blogCategory->getAttributes());
                $tableName = $blogCategory::tableName();

                // 先全部删除对应的栏目
                $sql = "DELETE FROM `{$tableName}`  WHERE `blog_id` = {$blogId}";
                Yii::$app->db->createCommand($sql)->execute();

                // 再批量插入栏目到BlogCategory::tableName()表
                Yii::$app->db->createCommand()->batchInsert(
                    $tableName, 
                    $attributes,
                    $data
                )->execute();

                // 提交
                $transaction->commit();
                return $this->redirect(['index']);
            } catch (Exception $e) {
                // 回滚
                $transaction->rollback();
                throw $e;
            }
        } else {
            // 获取博客关联的栏目
            $model->category = BlogCategory::getRelationCategorys($id);
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UserBackend model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserBackend model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return UserBackend the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Blog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
