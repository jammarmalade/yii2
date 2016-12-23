<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ImageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="image-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'type')->dropDownList(['' => '全部','1'=>'收支记录','0'=>'未使用']) ?>
    
    <?= $form->field($model, 'status')->dropDownList(['' => '全部','1'=>'正常','0'=>'删除','2' => '未使用']) ?>

    <?php // echo $form->field($model, 'size') ?>

    <?php // echo $form->field($model, 'width') ?>

    <?php // echo $form->field($model, 'height') ?>

    <?php // echo $form->field($model, 'width_thumb') ?>

    <?php // echo $form->field($model, 'height_thumb') ?>

    <?php // echo $form->field($model, 'exif') ?>

    <?php // echo $form->field($model, 'time_create') ?>

    <div class="form-group">
        <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
