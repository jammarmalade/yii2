<?php

namespace backend\components;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use mdm\admin\components\MenuHelper;
/**
 * Admin controller
 */
class AdminController extends Controller {
    
    private $nav1ActiveId ;
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
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


}
