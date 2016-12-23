<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Record */

$this->title = '新增收支';
$this->params['breadcrumbs'][] = ['label' => '收支列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="record-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
