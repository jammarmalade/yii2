<?php

namespace frontend\components;

use Yii;
use yii\web\Controller;
use yii\helpers\Json;
/**
 * Web controller
 */
class WebController extends Controller {
    
    protected $formatTime ;
    protected $time ;
    //图片
    public $imageUrl = '';
    /**
     * 初始化一些变量
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->time = time();
        $this->formatTime = date('Y-m-d H:i:s',$this->time);
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function message($data){
        if(!isset($data['title'])){
            $data['title'] = '错误提示';
        }
        return $this->render('/layouts/message', $data);
    }
    
    public function ajaxReturn($data , $msg = '', $status = false){
        $resData = [
            'data' => $data,
            'msg' => $msg,
            'status' => $status,
        ];
        return Json::encode($resData);
    }


}
