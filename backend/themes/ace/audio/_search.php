<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AudioSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="audio-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'content') ?>

    <?= $form->field($model, 'spd') ?>

    <?= $form->field($model, 'pit') ?>

    <?php // echo $form->field($model, 'vol') ?>

    <?php // echo $form->field($model, 'per') ?>

    <?php // echo $form->field($model, 'path') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'time_create') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
