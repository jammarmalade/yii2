<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Column */

$this->title = '新增栏目';
$this->params['breadcrumbs'][] = ['label' => '栏目列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="column-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
