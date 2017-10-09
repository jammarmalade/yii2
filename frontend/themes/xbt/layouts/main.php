<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::addCss($this, 'common.min.css');
AppAsset::addScript($this, 'common.js');
$confg = $this->params['config'];
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script>
    var _hmt = _hmt || [];
    (function() {
      var hm = document.createElement("script");
      hm.src = "https://hm.baidu.com/hm.js?c5086800fa16121de2279a538e62b16d";
      var s = document.getElementsByTagName("script")[0]; 
      s.parentNode.insertBefore(hm, s);
    })();
    </script>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <?php if(Yii::$app->controller->id=='site' && Yii::$app->controller->action->id=='index'){?>
    <div class="site-bg" style="background-color:rgb(255, 255, 255);background-image:url(https://ss2.bdstatic.com/kfoZeXSm1A5BphGlnYG/skin/257.jpg);">  </div>
    <?php }?>
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
                    <a class="navbar-brand" href="/"><?=$confg['siteName']?></a>
                </div>
                <div id="w0-collapse" class="collapse navbar-collapse">
                    <ul id="w1" class="navbar-nav nav">
                        <li <?php if(Yii::$app->controller->id=='site' && Yii::$app->controller->action->id=='index'){?>class="active"<?php }?>><a href="<?= Yii::$app->request->hostInfo ?>">首页</a></li>
                        <!--<li><a href="<?= Url::to(['site/about']) ?>">关于博客</a></li>-->
                        <!--<li><a href="<?= Url::to(['site/contact']) ?>">联系我</a></li>-->
                    </ul>
                    <ul id="w1" class="navbar-nav nav navbar-right">
                        <?php if(!Yii::$app->user->isGuest){?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= Yii::$app->user->identity->username?> <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="http://admin.jam00.com/" target="_blank">
                                            <i class="icon-cog"></i>
                                            后台管理
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= Url::to(['user/logout']) ?>" data-method="post">
                                            <span class="glyphicon glyphicon-log-out span-margin-left"></span>
                                            退出
                                        </a>
                                    </li>
                                </ul>
                            </li>
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
        <p class="pull-left"><?=$confg['beian']?></p>
        <p class="pull-right">
            <?=$confg['copyright']?>
        </p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
