<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::addCss($this, 'common.css');
AppAsset::addScript($this, 'common.js');
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
    <div class="wrap">
        
        <nav id="w0" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#w0-collapse">
                        <span class="sr-only"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">果酱</a>
                </div>
                <div id="w0-collapse" class="collapse navbar-collapse">
                    <ul id="w1" class="navbar-nav nav">
                        <li <?php if(Yii::$app->controller->id=='site' && Yii::$app->controller->action->id=='index'){?>class="active"<?php }?>><a href="<?= Url::to(['site/index']) ?>">首页</a></li>
                        <!--<li><a href="<?= Url::to(['site/about']) ?>">关于博客</a></li>-->
                        <!--<li><a href="<?= Url::to(['site/contact']) ?>">联系我</a></li>-->
                    </ul>
                    <ul id="w1" class="navbar-nav nav navbar-right">
                        <?php if(!Yii::$app->user->isGuest){?>
                            <li><a href="javascript:;"><?= Yii::$app->user->identity->username?></a></li>
                            <li><a href="<?= Url::to(['user/logout']) ?>">退出</a></li>
                        <?php }else{?>
                            <li <?php if(Yii::$app->controller->action->id=='signup'){?>class="active"<?php }?>><a href="<?= Url::to(['user/signup']) ?>">注册</a></li>
                            <li <?php if(Yii::$app->controller->action->id=='login'){?>class="active"<?php }?>><a href="<?= Url::to(['user/login']) ?>">登录</a></li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container" id="main">
        
        <?= Alert::widget() ?>
        <?= $content ?>
        </div>
    </div>
    
    <footer class="footer">
        <div class="container ba-area">
        <p class="pull-left"><a href="http://www.miibeian.gov.cn/" target="_blank">渝ICP备17011886号</a></p>
        <p class="pull-right">
            Copyright &copy; <a href="/">jam00.com</a> 2017
        </p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
