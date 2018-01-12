<?php

namespace api\controllers;

use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\ContentNegotiator;
use yii\web\Response;

//类名 Controller 之前不能用 驼峰命名
class ApiactiveController extends ActiveController {

    public $modelClass = '';
    public $timestamp;
    public $formatTime;
    public $request;
    public $uid = 0;
    public $username = '';

    public function init() {
        parent::init();
        $this->timestamp = $_SERVER['REQUEST_TIME'];
        $this->formatTime = date('Y-m-d H:i:s', $this->timestamp);
        $this->request = \Yii::$app->request;
    }

    public function behaviors() {
        return ['contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
//                    'application/xml' => Response::FORMAT_XML,//暂时不使用 xml
//                    'application/xml' => Response::FORMAT_HTML,//调试
                ]
            ]
        ];
    }

    public function object2array($object) {
        //http://www.yiichina.com/doc/guide/2.0/helper-array
        return ArrayHelper::toArray($object);
    }

    public function result($result, $message = '', $status = true) {
        $result = is_object($result) ? $this->object2array($result) : $result;
        return ['status' => $status, 'message' => $message, 'result' => $result];
    }
    public function resultError($message,$result = '' ) {
        header("content-type:text/json");
        exit(json_encode(['status' => false, 'message' => $message, 'result' =>$result ],JSON_UNESCAPED_UNICODE));
    }

    public function actionError() {
        header("content-type:text/json");
        exit(json_encode($this->result('', 'Illegal operation', false)));
    }

    /**
     * 获取请求数据
     * @param type $key     post.id / get.id
     * @param type $default 默认值
     * @param type $must    是否必须，若为1且没有输入值时，将直接返回错误信息
     */
    public function input($key, $default = '', $must = 0) {
        if (strpos($key, '.')) {
            list($method, $name) = explode('.', $key);
        } else {
            $name = $key;
            $method = 'get';
        }
        switch (strtolower($method)) {
            case 'get': $val = $this->request->get($name);
                break;
            case 'post': $val = $this->request->post($name);
                break;
        }
        $value = $val ? $val : $default;
        if(!$val && $must){
            return $this->resultError('缺少参数：'.$name);
        }
        return $value;
    }

    /**
     * 是否登录
     * @param type $err     是否强制提醒
     * @return boolean
     */
    public function isLogin($err = true){
        if($this->uid > 0 && $this->username){
            return true;
        }
        $authkey = $this->input('post.authkey','');
        if(!$authkey){
            if(!$err){
                return true;
            }else{
                $this->resultError('请先登录 - 001','');
            }
        }
        $decodeKey = \api\common\Functions::authcode($authkey);
        if(!$decodeKey && $err){
            $this->resultError('请先登录 - 002','');
        }
        list($this->uid,$this->username) = explode("\t", $decodeKey);
        if(!is_numeric($this->uid) && $err){
            $this->resultError('验证错误','');
        }
    }
}
