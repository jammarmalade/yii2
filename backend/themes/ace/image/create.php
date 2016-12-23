<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Image */

$this->title = '上传图片';
$this->params['breadcrumbs'][] = ['label' => '图片列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
