<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $model backend\models\Source */

$this->title = $model->subject;
$this->params['breadcrumbs'][] = ['label' => '数据列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
AppAsset::addScript($this, 'lazyload.min.js');
AppAsset::addScript($this, 'jquery.colorbox.min.js');

?>
<div class="source-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= $model->content?></p>
    <?php
    echo '<ul class="ace-thumbnails">';
    foreach($sourceImageList as $k=>$info){
        $url = \Yii::$app->params['SERVER_IMG'].$info->path;
        $defaultUrl = \Yii::$app->params['SERVER_IMG'].'static/image/404.png';
        $imgStr = Html::img($url,['class' => 'lazy','data-original' => $url , 'onerror'=>'this.src=\''.$defaultUrl.'\'']);
        echo '<li>'.Html::a($imgStr,$url, ['data-rel'=>"colorbox"]).'</li>';
    }
    echo '</ul>';
    ?>

</div>
<?php $this->beginBlock("view") ?>
jQuery(document).ready(function () {
    $("img.lazy").lazyload({
        effect: "fadeIn"
    });
    //图片浏览
    var colorbox_params = {
        reposition:true,
        scalePhotos:true,
        scrolling:false,
        previous:'<i class="icon-arrow-left"></i>',
        next:'<i class="icon-arrow-right"></i>',
        close:'&times;',
        current:'{current} of {total}',
        maxWidth:'100%',
        maxHeight:'100%',
        onOpen:function(){
            document.body.style.overflow = 'hidden';
        },
        onClosed:function(){
            document.body.style.overflow = 'auto';
        },
        onComplete:function(){
            $.colorbox.resize();
        }
    };
    $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
    $("#cboxLoadingGraphic").append("<i class='icon-spinner orange'></i>");
});
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks["view"], \yii\web\View::POS_END); ?>
