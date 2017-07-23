<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Source */

$this->title = '新增数据';
$this->params['breadcrumbs'][] = ['label' => '数据列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
