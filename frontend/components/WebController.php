<?php

namespace frontend\components;

use Yii;
use yii\web\Controller;
use yii\helpers\Json;
use common\models\Config;
use common\models\Column;

/**
 * Web controller
 */
class WebController extends Controller {

    protected $formatTime;
    protected $time;
    //图片
    public $imageUrl = '';
    //静态资源地址
    public $staticUrl = '';
    //默认图片地址
    protected $defaultArticlItemImg = '';
    //默认头像
    protected $defaultHeadImg = '';
    //视图属性
    protected $view = '';
    //是否是手机端
    protected $mobile = false;
    //配置信息
    public $config = [];
    //导航列表
    public $columnList = [];
    //用户id
    protected $uid = 0;
    //用户昵称
    protected $username = '';
    //是否是ajax
    protected $ajax = false;
    /**
     * 初始化一些变量
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->time = time();
        $this->view = $this->getView();
        $this->formatTime = date('Y-m-d H:i:s', $this->time);
        $this->view->params['staticBaseUrl'] = $this->staticUrl = Yii::$app->view->theme->baseUrl;
        $this->view->params['staticImgUrl'] = $this->staticUrl . '/images';
        $this->view->params['staticCssUrl'] = $this->staticUrl . '/css';
        $this->view->params['staticJsUrl'] = $this->staticUrl . '/js';
        $this->view->params['defaultArticlItemImg'] = $this->defaultArticlItemImg = $this->staticUrl . '/images/articl-item.jpg';
        $this->view->params['defaultHeadImg'] = $this->defaultHeadImg = $this->staticUrl . '/images/default_head.png';
        $this->imageUrl = Yii::$app->params['imgDomain'];
        $this->view->params['mobile'] = $this->mobile = $this->checkmobile(); //是否是手机端访问
        //配置缓存
        $this->view->params['config'] = $this->config = Config::getConfig();
        //主导航栏
        $columnModel = new Column();
        $this->columnList = $columnModel->getColumnList();
        $this->view->params['menuList'] = [];
        if(isset($this->columnList['formatNavMul'][1]['cnav'])){
           $this->view->params['menuList'] = $this->columnList['formatNavMul'][1]['cnav'];
        }
        //用户id和昵称
        if(!Yii::$app->user->isGuest){
            $this->uid = Yii::$app->user->identity->id;
            $this->username = Yii::$app->user->identity->username;
        }
        $this->ajax = Yii::$app->request->isAjax;
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

    public function message($data) {
        if (!isset($data['title'])) {
            $data['title'] = '错误提示';
        }
        return $this->render('/layouts/message', $data);
    }

    public function ajaxReturn($data, $msg = '', $status = false) {
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

    private function checkmobile() {

        $mobile = array();
        static $mobilebrowser_list = array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
            'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
            'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
            'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
            'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
            'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
            'benq', 'haier', '^lct', '320x320', '240x320', '176x220');
        $pad_list = array('pad', 'gt-p1000');

        $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if ($this->dstrpos($useragent, $pad_list)) {
            return false;
        }
        if (($v = $this->dstrpos($useragent, $mobilebrowser_list, true))) {
            //手机端设备名称
//            $this->platform = $v;
            return true;
        }
        $brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
        if ($this->dstrpos($useragent, $brower))
            return false;
    }

    private function dstrpos($string, &$arr, $returnvalue = false) {
        if (empty($string))
            return false;
        foreach ((array) $arr as $v) {
            if (strpos($string, $v) !== false) {
                $return = $returnvalue ? $v : true;
                return $return;
            }
        }
        return false;
    }

}
