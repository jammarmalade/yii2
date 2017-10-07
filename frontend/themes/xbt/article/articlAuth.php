<?php

use frontend\assets\AppAsset;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use frontend\components\Functions as tools;
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = '请输入查看密码';
?>
<style type="text/css">
    .article-auth{
        text-align: center;
    }
    #title{
        font-size: 22px;
        font-weight: 700;
    }
    #auth{
        margin: 10px auto;
        width:20%;
    }
    #tips{
        font-size: 14px;
        color: #FE4365;
    }
</style>
<div class="article-auth box">
    <div id="title">本内容需要密码才可查看</div>
    <?php $form = ActiveForm::begin(['id' => 'auth-form']); ?>
    <input type="password" placeholder="请输入查看密码" id='auth' name='auth' class="form-control">
        <div class="form-group">
            <?= Html::submitButton('查看', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    <?php if($msg){?>
    <div id='tips'>
        <?=$msg?>
    </div>
    <?php }?>
</div>



