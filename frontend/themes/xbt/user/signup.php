<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = '注册';
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>请填写如下信息</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id' => 'form-signup',
                'enableAjaxValidation' => true,
                ]);
            ?>
                <?= $form->field($model, 'username')->label('用户名')->textInput(['actufocus' => true,'autocomplete ' => 'off'])?>
                <?= $form->field($model, 'email')->label('邮箱')->textInput(['autocomplete ' => 'off'])?>
                <?= $form->field($model, 'password')->label('密码')->passwordInput()?>
                <div class="form-group">
                    <?= Html::submitButton('注册', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
