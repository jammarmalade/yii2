<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $model backend\models\Source */

$this->title = $model->subject;
$this->params['breadcrumbs'][] = ['label' => '数据列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
AppAsset::addCss($this, 'viewer.min.css');
AppAsset::addScript($this, 'lazyload.min.js');
AppAsset::addScript($this, 'viewer.min.js');

?>
<div class="source-view">

    <h1><?= Html::encode($this->title) ?>【<?=count($sourceImageList)?>】</h1>

    <p><?= $model->content?></p>
    
    <div id="imgs">
    <?php
    foreach($sourceImageList as $k=>$info){
        $url = \Yii::$app->params['SERVER_IMG'].$info->path;
        $defaultUrl = \Yii::$app->params['SERVER_IMG'].'static/image/404.png';
        $imgStr = Html::img($url,['class' => 'lazy','data-big' => $url , 'onerror'=>'this.src=\''.$defaultUrl.'\'']);
        echo $imgStr;
    }
    ?>
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
