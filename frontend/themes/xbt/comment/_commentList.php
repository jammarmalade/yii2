<?php

use yii\widgets\LinkPager;
?>
<div id="list_data">
    <?php 
    foreach($dataList as $k=>$comment){
        echo $this->render('_commentItem', ['comment' => $comment]);
    }
    ?>
</div>
<div id="list_page" class="div-page">
<?= LinkPager::widget([
    'pagination' => $pages,
    'maxButtonCount'=>5,
    'nextPageLabel' => '下一页', 
    'prevPageLabel' => '上一页', 
    ]); ?>
</div>