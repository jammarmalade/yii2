<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserBackend */

$this->title = '添加新用户';
$this->params['breadcrumbs'][] = ['label' => '用户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-backend-signup">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'form-signup']);?>
        <?= $form->field($model, 'username')->label('用户名')->textInput(['actufocus' => true])?>
        <?= $form->field($model, 'email')->label('邮箱')?>
        <?= $form->field($model, 'password')->label('密码')->passwordInput()?>
        <div class="form-group">
            <?= Html::submitButton('注册', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
