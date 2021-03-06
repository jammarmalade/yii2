<?php

namespace backend\components;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use mdm\admin\components\MenuHelper;
use yii\helpers\Json;
/**
 * Admin controller
 */
class AdminController extends Controller {

    private $nav1ActiveId ;
    protected $formatTime ;
    protected $time ;
    /**
     * 初始化一些变量
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $view = Yii::$app->getView();
        $view->params['menuList'] = MenuHelper::getAssignedMenu(Yii::$app->user->id ,NULL ,function($menu){
            return $this->menuListCallBack($menu);
        });
        $view->params['activeId'] = $this->nav1ActiveId;
        $this->time = time();
        $this->formatTime = date('Y-m-d H:i:s',$this->time);
    }
    //menulist callback
    public function menuListCallBack($menu){
        $active = '';
        if('/'.Yii::$app->requestedRoute == $menu['route']){
            $active = 'class="active"';
            $this->nav1ActiveId = $menu['parent'];
        }
        $return = [
            'id' => $menu['id'],
            'label' => $menu['name'],
            'url' => $menu['route'] ? Url::to([$menu['route']]) : 'javascript:;',
            'route' => $menu['route'],
            'icon' => $menu['data'],
            'items' => $menu['children'],
            'active' => $active,
        ];
        return $return;
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','logout'],
                        'allow' => true,
                    ],
                    [
                        // 当前rule将会针对这里设置的actions起作用，如果actions不设置，默认就是当前控制器的所有操作
//                        'actions' => ['logout', 'index','view', 'update', 'delete', 'signup','create'],
                        // 设置actions的操作是允许访问还是拒绝访问
                        'allow' => true,
                        // @ 当前规则针对认证过的用户; ? 所有方法均可访问
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
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
    /**
     * 提示信息
     * @param string $data ['title' => '提示标题' ,'msg' => '错误信息']
     * @return type
     */
    public function message($data){
        if(!isset($data['title'])){
            $data['title'] = '错误提示';
        }
        return $this->render('/site/message', $data);
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
