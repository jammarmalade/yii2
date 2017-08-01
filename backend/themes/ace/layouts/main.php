<?php

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
AppAsset::addCss($this, 'bootstrap.min.css');
AppAsset::addCss($this, 'jquery-ui-1.10.3.full.min.css');
AppAsset::addCss($this, 'font-awesome.min.css');
AppAsset::addCss($this, 'http://fonts.googleapis.com/css?family=Open+Sans:400,300');
AppAsset::addCss($this, 'ace.min.css');
AppAsset::addCss($this, 'ace-rtl.min.css');
AppAsset::addCss($this, 'ace-skins.min.css');
AppAsset::addCss($this, 'ace-custom.css');
//AppAsset::addScript($this, 'jquery.min.js');
AppAsset::addScript($this, 'bootstrap.min.js');
AppAsset::addScript($this, 'ace-extra.min.js');
AppAsset::addScript($this, 'jquery.mobile.custom.min.js');
//AppAsset::addScript($this, 'typeahead-bs2.min.js');//自动完成插件 AutoComplete
//AppAsset::addScript($this, 'jquery-ui-1.10.3.custom.min.js');
//AppAsset::addScript($this, 'jquery.ui.touch-punch.min.js');//移动端的触摸插件 
//AppAsset::addScript($this, 'jquery.slimscroll.min.js');//滚动条插件
AppAsset::addScript($this, 'ace-elements.min.js');
AppAsset::addScript($this, 'ace.min.js');


$this->registerMetaTag(['name' => 'keywords', 'content' => 'jam00,后台管理']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="navbar navbar-default" id="navbar">
            <script type="text/javascript">
                try {
                    ace.settings.check('navbar', 'fixed')
                } catch (e) {
                }
            </script>

            <div class="navbar-container" id="navbar-container">
                <div class="navbar-header pull-left">
                    <a href="#" class="navbar-brand">
                        <small>
                            <i class="icon-leaf"></i>
                            JAM00后台管理
                        </small>
                    </a><!-- /.brand -->
                </div><!-- /.navbar-header -->

                <div class="navbar-header pull-right" role="navigation">
                    <ul class="nav ace-nav">
                        <li class="purple">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <i class="icon-bell-alt icon-animated-bell"></i>
                                <span class="badge badge-important">8</span>
                            </a>
                            <ul class="pull-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
                                <li class="dropdown-header">
                                    <i class="icon-warning-sign"></i>
                                    1条通知
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">
                                                <i class="btn btn-xs no-hover btn-pink icon-comment"></i>
                                                新评论
                                            </span>
                                            <span class="pull-right badge badge-info">+12</span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        查看所有通知
                                        <i class="icon-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="light-blue">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                <img class="nav-user-photo" src="/static/images/user.png" alt="Admin" />
                                <span class="user-info">
                                    <small>欢迎</small>
                                    <?= Yii::$app->user->identity->username?>
                                </span>
                                <i class="icon-caret-down"></i>
                            </a>

                            <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                                <li>
                                    <a href="#">
                                        <i class="icon-cog"></i>
                                        设置
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="<?= Url::to(['/site/logout'])?>" data-method="post">
                                        <i class="icon-off"></i>
                                        退出
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul><!-- /.ace-nav -->
                </div><!-- /.navbar-header -->
            </div><!-- /.container -->
        </div>
        <!-- /.main-container -->
        <div class="main-container" id="main-container">
            <script type="text/javascript">
                try {
                    ace.settings.check('main-container', 'fixed')
                } catch (e) {
                }
            </script>
            <div class="main-container-inner">
                <a class="menu-toggler" id="menu-toggler" href="#">
                    <span class="menu-text"></span>
                </a>
                <!-- /.sidebar -->
                <div class="sidebar" id="sidebar">
                    <script type="text/javascript">
                        try{
                            ace.settings.check('sidebar' , 'fixed')
                        }catch(e){
                        }
                    </script>
                    <!-- #sidebar-shortcuts -->
                    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                            <button class="btn btn-success">
                                <i class="icon-signal"></i>
                            </button>
                            <button class="btn btn-info">
                                <i class="icon-pencil"></i>
                            </button>
                            <button class="btn btn-warning">
                                <i class="icon-group"></i>
                            </button>
                            <button class="btn btn-danger">
                                <i class="icon-cogs"></i>
                            </button>
                        </div>
                        <!-- unused -->
                        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                            <span class="btn btn-success"></span>
                            <span class="btn btn-info"></span>
                            <span class="btn btn-warning"></span>
                            <span class="btn btn-danger"></span>
                        </div>
                    </div>
                    <!-- #sidebar-nav -->
                    <ul class="nav nav-list">
                        <li <?php if(Yii::$app->controller->id == 'site'){?>class="active"<?php }?>>
                            <a href="/">
                                <i class="icon-dashboard"></i>
                                <span class="menu-text"> 首页 </span>
                            </a>
                        </li>
                        <?php 
                        if($this->params['menuList']){
                            foreach($this->params['menuList'] as $k => $menu){
                        ?>
                            <li <?php if($menu['id']==$this->params['activeId']){?>class="active"<?php }?>>
                                <?php if($menu['items']){?>
                                <a href="<?= $menu['url']?>" class="dropdown-toggle">
                                    <?php if($menu['icon']){?><i class="<?= $menu['icon']?>"></i><?php }?>
                                    <span class="menu-text"> <?= $menu['label']?> </span>
                                    <b class="arrow icon-angle-down"></b>
                                </a>
                                <ul class="submenu">
                                    <?php
                                    foreach($menu['items'] as $item){
                                    ?>
                                    <li <?= $item['active']?>>
                                        <a href="<?= $item['url']?>">
                                            <?php if($item['icon']){?>
                                            <i class="<?= $item['icon']?>"></i>
                                            <?php }else{?>
                                            <i class="icon-double-angle-right"></i>
                                            <?php }?>
                                            <?= $item['label']?>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                                <?php }else{?>
                                <a href="<?= $menu['url']?>">
                                    <?php if($menu['icon']){?><i class="<?= $menu['icon']?>"></i><?php }?>
                                    <span class="menu-text"> <?= $menu['label']?> </span>
                                </a>
                                <?php }?>
                            </li>
                        <?php }}?>
                    </ul>
                    <!-- /.nav-list -->
                    <div class="sidebar-collapse" id="sidebar-collapse">
                        <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
                    </div>
                    <script type="text/javascript">
                        try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
                    </script>
                    <!-- ace-settings -->
                    <div class="ace-settings-container" id="ace-settings-container">
                        <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                            <i class="icon-cog bigger-150"></i>
                        </div>
                        <div class="ace-settings-box" id="ace-settings-box">
                            <div>
                                <div class="pull-left">
                                    <select id="skin-colorpicker" class="hide">
                                        <option data-skin="default" value="#438EB9">#438EB9</option>
                                        <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                                        <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                                        <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                                    </select>
                                </div>
                                <span>&nbsp; 选择皮肤</span>
                            </div>
                            <div>
                                <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
                                <label class="lbl" for="ace-settings-navbar"> 固定导航条</label>
                            </div>

                            <div>
                                <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
                                <label class="lbl" for="ace-settings-sidebar"> 固定滑动条</label>
                            </div>

                            <div>
                                <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
                                <label class="lbl" for="ace-settings-breadcrumbs">固定面包屑</label>
                            </div>

                            <div>
                                <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
                                <label class="lbl" for="ace-settings-rtl">切换到左边</label>
                            </div>

                            <div>
                                <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
                                <label class="lbl" for="ace-settings-add-container">
                                    切换窄屏
                                    <b></b>
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- ./ace-settings -->
                </div>
                <!-- /.main-content -->
                <div class="main-content">
                    <div class="breadcrumbs" id="breadcrumbs">
                        <script type="text/javascript">
                                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                        </script>
                        <!-- .breadcrumb -->
                        <?= Breadcrumbs::widget([
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]) ?>
                        <div class="nav-search" id="nav-search">
                            <form class="form-search">
                                <span class="input-icon">
                                    <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                                    <i class="icon-search nav-search-icon"></i>
                                </span>
                            </form>
                        </div>
                        <!-- #nav-search -->
                    </div>
                    <!-- main content -->
                    <div class="page-content">
                        <?= $content ?>
                        
                    </div>
                </div>
            </div>
        </div>


        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
