<?php

namespace api\modules\v1\controllers;

use api\controllers\ApiactiveController;
use api\modules\v1\models\District;
use api\common\Functions;

class WeatherController extends ApiactiveController
{
    public $modelClass = 'api\modules\v1\models\district';
    
//    public function behaviors() {
//
//        $behaviors = parent::behaviors();
//        $behaviors['authenticator'] = [
//            'class' => HttpBasicAuth::className(),
//        ];
//        return $behaviors;
//    }

    public function actions() {
        
        $actions = parent::actions();

        // 禁用"delete" 和 "create" 操作
        unset($actions['delete'], $actions['create']);

        // 使用"prepareDataProvider()"方法自定义数据provider 
//        $actions['index']['prepareDataProvider'] = [$this, 'getProvince'];

        return $actions;
    }
    //返回所有省级
    public function actionProvince() {
        $provinces = District::find()->where(['level'=>1])->all();
        return $this->result($provinces);
    }
    //返回省下面的城市
    public function actionCity() {
        $upid = $_GET['id'];
        $citys = District::find()->where(['upid'=>$upid,'level'=>2])->all();
        return $this->result($citys);
    }
    //返回城市下面的区县
    public function actionArea() {
        $upid = $_GET['id'];
        $areas = District::find()->where(['upid'=>$upid,'level'=>3])->all();
        return $this->result($areas);
    }
    //获取城市id
    public function actionWeather(){
        $cityCode = $_GET['id'];
    }
}