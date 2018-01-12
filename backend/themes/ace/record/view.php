<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\assets\AppAsset;

AppAsset::addCss($this, 'viewer.min.css');
AppAsset::addScript($this, 'lazyload.min.js');
AppAsset::addScript($this, 'viewer.min.js');
/* @var $this yii\web\View */
/* @var $model backend\models\Record */

$this->title = '查看收支记录 - '.$model->id;
$this->params['breadcrumbs'][] = ['label' => '收支列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    #imgs img{
        display: block;
        margin-top: 10px;
        border: 1px solid #c1c1c1;
    }
</style>
<div class="record-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确认删除？',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'uid',
            'username',
            'account',
            'type',
            'content:ntext',
            'imgstatus',
            'longitude',
            'latitude',
            'weather',
            'remark',
            'time_create',
        ],
    ]) ?>

    <div>
        <h1>记录图片</h1>
        <div id="imgs">
            <?php
            if($imageList){
                $imgDomain = Yii::$app->params['imgDomain'];
                foreach($imageList as $k=>$info){
                    $info['imgUrl'] = $info['imgUrlThumb'] = $imgDomain.$info['path'];
                    if($info['thumb']){
                        $info['imgUrlThumb'] = $info['imgUrl'].'.thumb.jpg';
                    }
                    $imgStr = Html::img($info['imgUrlThumb'],['class' => 'lazy','data-big' => $info['imgUrl']]);
                    echo $imgStr;
                }
            }
            ?>
        </div>
    </div>

</div>
<?php $this->beginBlock("view") ?>
jQuery(document).ready(function () {
    $("img.lazy").lazyload({
        effect: "fadeIn"
    });
    //图片查看
    var viewer = new Viewer(document.getElementById('imgs'), {
        url: 'data-big',
        title: false,
        navbar: false
    });
});
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks["view"], \yii\web\View::POS_END); ?>
