<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Column */

$this->title = '修改栏目: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '栏目列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="column-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
