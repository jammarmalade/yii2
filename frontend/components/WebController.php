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
    //静态资源地址
    public $staticUrl = '';
    //默认图片地址
    public $defaultArticlItemImg = '';
    //视图属性
    public $view = '';
    /**
     * 初始化一些变量
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->time = time();
        $this->view = $this->getView();
        $this->formatTime = date('Y-m-d H:i:s',$this->time);
        $this->view->params['staticBaseUrl'] = $this->staticUrl = Yii::$app->view->theme->baseUrl;
        $this->view->params['staticImgUrl'] = $this->staticUrl.'/images';
        $this->view->params['staticCssUrl'] = $this->staticUrl.'/css';
        $this->view->params['staticJsUrl'] = $this->staticUrl.'/js';
        $this->view->params['defaultArticlItemImg'] = $this->defaultArticlItemImg = $this->staticUrl.'/images/articl-item.jpg';
        $this->imageUrl = Yii::$app->params['imgDomain'];
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
    /**
     * 获取请求数据
     * @param type $key     post.id / get.id
     * @param type $default 默认值
     */
    public function input($key, $default = '') {
        if (strpos($key, '.')) {
            list($method, $name) = explode('.', $key);
        } else {
            $name = $key;
            $method = 'get';
        }
        switch (strtolower($method)) {
            case 'get': $val = Yii::$app->request->get($name);
                break;
            case 'post': $val = Yii::$app->request->post($name);
                break;
        }
        $value = $val ? $val : $default;
        return $value;
    }


}
