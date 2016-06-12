<?php

namespace api\modules\v1\controllers;

use api\controllers\ApiactiveController;
use api\modules\v1\models\District;
use api\common\Functions;
use api\common\XML2Array;

class WeatherController extends ApiactiveController
{
    public $modelClass = 'api\modules\v1\models\district';
    const GET_WEATHER_URL = 'http://wthrcdn.etouch.cn/WeatherApi?citykey=';


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
        $provinces = District::find()->where(['level'=>1])->orderBy('order_number DESC')->all();
        return $this->result($provinces);
    }
    //返回省下面的城市
    public function actionCity() {
        $upid = $_GET['id'];
        $citys = District::find()->where(['upid'=>$upid,'level'=>2])->orderBy('order_number DESC')->all();
        return $this->result($citys);
    }
    //返回城市下面的区县
    public function actionArea() {
        $upid = $_GET['id'];
        $areas = District::find()->where(['upid'=>$upid,'level'=>3])->orderBy('order_number DESC')->all();
        return $this->result($areas);
    }
    //获取城市id
    public function actionWeather(){
        $cityCode = $_GET['id'];
        //缓存key 
        $sKey = 'weather'.$cityCode;
        //获取缓存
        $cacheData = \yii::$app->cache->get($sKey);
        if($cacheData){
            return $this->result($cacheData);
        }
        $url = self::GET_WEATHER_URL.$cityCode;
        $xml = preg_replace('/<!--.+?-->/is','',Functions::myCurl($url));//去除 <!----> 注释内容，防止转义成 数组时出错
        $weather = XML2Array::createArray($xml)['resp'];
        //若是有错误信息
        if(isset($weather['error'])){
            return $this->result('', $weather['error'], false);
        }else{
            $weather['code'] = $cityCode;
            //存入缓存并返回
            $cacheData['updateTime'] = $this->timestamp;
            $cacheData['data'] = $weather;
            \yii::$app->cache->set($sKey,$cacheData,3600);//缓存一个小时
            return $this->result($cacheData);
        }
    }
}