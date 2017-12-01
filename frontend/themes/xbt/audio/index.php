<?php

use frontend\assets\AppAsset;
use yii\helpers\Url;
use frontend\components\Functions as tools;

AppAsset::addCss($this, 'audio.css');
AppAsset::addScript($this, 'audio.js');

/* @var $this yii\web\View */
$confg = $this->params['config'];
$this->title = '语音合成 - '.$confg['siteName'];
?>
<div class="site-index box">
    <div class="audio-title">语音合成</div>
    <div class="audio-content-area">
        <div>请输入合成文本（暂时只支持 512 个字节）</div>
        <textarea id="content"></textarea>
    </div>
    <div>
        <span class="option-title">语速（推荐4）</span>
        <select id="spd" class="audio-select">
            <?php
            for ($i = 0; $i <= 9; $i++) {
                $select = '';
                if($i==4){
                    $select = 'selected';
                }
                echo '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
            }
            ?>
        </select>
    </div>
    <div>
        <span class="option-title">音调（推荐5）</span>
        <select id="pit" class="audio-select">
            <?php
            for ($i = 0; $i <= 9; $i++) {
                $select = '';
                if($i==5){
                    $select = 'selected';
                }
                echo '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
            }
            ?>
        </select>
    </div>
    <div>
        <span class="option-title">音量（推荐5）</span>
        <select id="vol" class="audio-select">
            <?php
            for ($i = 0; $i <= 15; $i++) {
                $select = '';
                if($i==5){
                    $select = 'selected';
                }
                echo '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
            }
            ?>
        </select>
    </div>
    <div>
        <span class="option-title">发音人</span>
        <span class="audio-pople audio-pople-active" data-id="0">普通女声</span>
        <span class="audio-pople" data-id="1">普通男声</span>
        <span class="audio-pople" data-id="3">情感合成-男声</span>
        <span class="audio-pople" data-id="4">情感合成-女声</span>
    </div>
    <div class="clearfix">
        <a href="javascript:;" id="create">合成语音</a>
    </div>
    <div id="audio_list">
        <?php
        foreach($dataList as $k=>$v){
        ?>
            <div class="audio-item">
                <span class="audio-item-text"><?=$v['preTitle']?></span>
                <audio src="<?=$v['path']?>" controls="controls">您的浏览器不支持 audio 标签。</audio>
                <div class="audio-item-content">内容：<?=$v['cut_content']?></div>
                <div class="audio-item-time">生成时间：<?=$v['time_create']?></div>
            </div>
        <?php }?>
    </div>

</div>
<?php $this->beginBlock("links") ?>
window.URL_AUDIO = '<?php echo Url::to(['audio/create']);?>';
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks["links"], \yii\web\View::POS_HEAD); ?>

