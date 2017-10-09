<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\AppAsset;

AppAsset::addCss($this, 'login.min.css',false, yii\web\View::POS_BEGIN);

$this->title = '注册';
?>
<div class="site-signup box">
    <div class="login-title"><?= Html::encode($this->title) ?></div>

    <div class="row">
        <div class="col-lg-12">
            <?php $form = ActiveForm::begin([
                'id' => 'form-signup',
                'enableAjaxValidation' => true,
                ]);
            ?>
                <?= $form->field($model, 'username')->label('用户名')->textInput(['actufocus' => true,'autocomplete ' => 'off'])?>
                <?= $form->field($model, 'email')->label('邮箱')->textInput(['autocomplete ' => 'off'])?>
                <?= $form->field($model, 'password')->label('密码')->passwordInput()?>
                <?= $form->field($model, 'password1')->label('确认密码')->passwordInput()?>
                <div class="form-group">
                    <?= Html::submitButton('注册', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
