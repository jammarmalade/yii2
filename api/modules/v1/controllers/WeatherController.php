<?php

namespace api\modules\v1\controllers;

use api\controllers\ApiactiveController;
use backend\models\District;
use api\common\Functions;
use api\common\XML2Array;

class WeatherController extends ApiactiveController
{
    public $modelClass = 'backend\models\District';
    const GET_WEATHER_URL = 'http://wthrcdn.etouch.cn/WeatherApi?citykey=';
    const GET_WEATHER_URL_HF = 'https://free-api.heweather.com/v5/weather?key=41b1c534a9284d5785e21ef1e3ac38fe&city=';
    //和风天气接口 https://free-api.heweather.com/v5/weather?city=CN101270608&key=41b1c534a9284d5785e21ef1e3ac38fe

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
        return $this->weatherResult($provinces);
    }
    //返回省下面的城市
    public function actionCity() {
        $upid = $_GET['id'];
        $citys = District::find()->where(['upid'=>$upid,'level'=>2])->orderBy('order_number DESC')->all();
        return $this->weatherResult($citys);
    }
    //返回城市下面的区县
    public function actionArea() {
        $upid = $_GET['id'];
        $areas = District::find()->where(['upid'=>$upid,'level'=>3])->orderBy('order_number DESC')->all();
        return $this->weatherResult($areas);
    }
    //获取城市id
    public function actionWeather(){
        $cityCode = $_GET['id'];
        return $this->weatherResult($this->getWeatherHF($cityCode));
    }
    //获取天气信息
    private function getWeather($cityCode){
        //缓存key 
        $sKey = 'weather'.$cityCode;
        //获取缓存
        $cacheData = \yii::$app->cache->get($sKey);
        if($cacheData){
            return $cacheData;
        }
        $url = self::GET_WEATHER_URL.$cityCode;
        $xml = preg_replace('/<!--.+?-->/is','',Functions::curlHeader($url));//去除 <!----> 注释内容，防止转义成 数组时出错
        
        $weather = XML2Array::createArray($xml)['resp'];
        //若是有错误信息
        if(isset($weather['error'])){
            return $weather;
        }else{
            $weather['code'] = $cityCode;
            //存入缓存并返回
            $cacheData['updateTime'] = $this->timestamp;
            $cacheData['data'] = $weather;
            \yii::$app->cache->set($sKey,$cacheData,3600);//缓存一个小时
            return $cacheData;
        }
    }
    //获取天气信息 和风接口
    private function getWeatherHF($cityCode){
        //缓存key 
        $sKey = 'weather-hf'.$cityCode;
        //获取缓存
        $cacheData = \yii::$app->cache->get($sKey);
        if($cacheData){
            return $cacheData;
        }
        $url = self::GET_WEATHER_URL_HF.$cityCode;
        $weather = json_decode(file_get_contents($url),true);
        $cacheData['HeWeather'] = $weather['HeWeather5'];
        
        \yii::$app->cache->set($sKey,$cacheData,3600);//缓存一个小时
        return $cacheData;
    }
    //天气接口返回数据
    private function weatherResult($data){
        return is_object($data) ? $this->object2array($data) : $data;
    }
}