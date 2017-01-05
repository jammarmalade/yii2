<?php

namespace api\modules\v1\controllers;

use api\controllers\ApiactiveController;
use api\common\Functions;
use backend\models\UserBackend as User;

class UserController extends ApiactiveController
{
    //用户登录
    public function actionLogin(){
        $username = $this->input('post.username', '', 1);
        $password = $this->input('post.password', '', 1);
        $userInfo = User::findByUsername($username);
        if(!$userInfo){
            $this->resultError('该用户不存在');
        }
        if(!\Yii::$app->security->validatePassword($password, $userInfo['password'])){
            $this->resultError('密码错误');
        }
        return $userInfo;
    }
    
}