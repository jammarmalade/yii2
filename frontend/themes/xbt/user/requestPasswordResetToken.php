<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use frontend\assets\AppAsset;

AppAsset::addCss($this, 'login.min.css',false, yii\web\View::POS_BEGIN);

$this->title = '重置密码';
?>
<div class="site-login box">
    <div class="login-title"><?= Html::encode($this->title) ?></div>

    <div class="row">
        <div class="col-lg-12">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                <?= $form->field($model, 'email')->label('注册时填写的邮箱') ?>
                <div class="form-group">
                    <?= Html::submitButton('发送邮件', ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
