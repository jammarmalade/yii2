<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tag-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'autocomplete ' => 'off']) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'img')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?php if($model->isNewRecord){?>
            <?= Html::submitButton('新增', ['class' => 'btn btn-success' , 'name' => 'submitBtn']) ?>
            <?= Html::submitButton('新增并继续', ['class' => 'btn btn-success' , 'name' => 'submitBtn', 'value' => 'continue']) ?>
        <?php }else{?>
            <?= Html::submitButton('修改', ['class' => 'btn btn-primary' , 'name' => 'submitBtn']) ?>
        <?php }?>
        
    </div>

    <?php ActiveForm::end(); ?>

</div>
