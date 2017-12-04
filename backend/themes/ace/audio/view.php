<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Audio */

$this->title = $model->id.' - '.$model->username;
$this->params['breadcrumbs'][] = ['label' => '语音合成列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audio-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'uid',
            'username',
            'spd',
            'pit',
            'vol',
            'per',
            'path',
            'status',
            'time_create',
        ],
    ]) ?>
    <h2>语音内容</h2>
    <div>
        <?php
            echo '<audio src="'.Yii::$app->params['staticDomain'].$model->path.'" controls="controls">您的浏览器不支持 audio 标签。</audio>';
        ?>
    </div>
    <h2>合成内容</h2>
    <div><?=$model->content?></div>

</div>
