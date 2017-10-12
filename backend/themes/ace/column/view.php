<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Column */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '栏目列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="column-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'url:url',
            'status',
            'order_number',
            'create_time',
            'class',
            'pid',
            'remark',
        ],
    ]) ?>

</div>
