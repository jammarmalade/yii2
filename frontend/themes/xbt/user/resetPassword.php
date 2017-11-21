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
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                <?= $form->field($model, 'password')->passwordInput()->label('请输入新的密码') ?>
                <?= $form->field($model, 'password1')->label('确认新密码')->passwordInput()?>
                <div class="form-group">
                    <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
