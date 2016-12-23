<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Record */

$this->title = '修改收支: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '收支列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '查看收支记录 - '.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="record-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
