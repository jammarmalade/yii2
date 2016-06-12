<?php

namespace api\controllers;

use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
//类名 Controller 之前不能用 驼峰命名
class ApiactiveController extends ActiveController{
    
    public $modelClass = '';
//    
//    public function init(){
//        \Yii::$app->errorHandler->errorAction = 'site/apiError';
//    }
    
    public function object2array($object){
        //http://www.yiichina.com/doc/guide/2.0/helper-array
        return ArrayHelper::toArray($object);
    }
    public function result($result ,$message = '' ,$status = true){
        $result = $result ? $this->object2array($result) : $result;
        return ['status'=>$status, 'message'=> $message,'result'=>$result];
    }
    
    public function actionError(){
        header("content-type:text/json");
        exit(json_encode($this->result('', 'Illegal operation', false)));
    }
}

