<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\LoginForm;
use backend\components\AdminController;
/**
 * Site controller
 */
class SiteController extends AdminController {
    
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionLogin() {
        // 判断用户是访客还是认证用户 
        // isGuest为真表示访客，isGuest非真表示认证用户，认证过的用户表示已经登录了，这里跳转到主页面
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        // 实例化登录模型 common\models\LoginForm
        $model = new LoginForm();
        // 接收表单数据并调用LoginForm的login方法
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            // 非post直接渲染登录表单
            return $this->renderPartial('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
