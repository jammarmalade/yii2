<?php

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
AppAsset::addCss($this, 'font-awesome.min.css');
AppAsset::addCss($this, 'http://fonts.googleapis.com/css?family=Open+Sans:400,300');
AppAsset::addCss($this, 'ace.min.css');
AppAsset::addCss($this, 'ace-rtl.min.css');

AppAsset::addScript($this, 'bootstrap.min.js');
AppAsset::addScript($this, 'jquery.mobile.custom.min.js');

$this->title = '后台管理 - 登录';
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
    <body class="login-layout">
        <?php $this->beginBody() ?>
        
        <div class="main-container">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">
                            <div class="center">
                                <h1>
                                    <i class="icon-book green"></i>
                                    <span class="red">Jam00</span>
                                    <span class="white">后台登录</span>
                                </h1>
                            </div>
                            <div class="space-6"></div>
                            <!-- login -->
                            <div class="position-relative">
                                <div id="login-box" class="login-box visible widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header blue lighter bigger">
                                                <i class="icon-coffee green"></i>
                                                请先登录
                                            </h4>
                                            <div class="space-6"></div>
                                            <!-- login form -->
                                            <?php $form = ActiveForm::begin([
                                                'id' => 'login-form',
                                                'fieldConfig' => [  
                                                    'template' => "<label class=\"block clearfix\"><span class=\"block input-icon input-icon-right\">{input}</span>{error}</label>",  
                                                ],
                                            ]); ?>
                                                <?= $form->field($model, 'username')->input('text', ['placeholder'=>'用户名','autofocus' => 'autofocus','autocomplete'=>'off']) ?>
                                                <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'密码','autocomplete' => 'off']) ?>
                                                <?= $form->field($model, 'rememberMe')->checkbox()->label('记住我') ?>
                                                <div class="form-group">
                                                    <?= Html::submitButton('<i class="icon-key"></i>登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                                                </div>
                                            <?php ActiveForm::end(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /login  -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
