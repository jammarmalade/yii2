<?php

use frontend\assets\AppAsset;
use frontend\components\Functions as tools;

AppAsset::addCss($this, 'chat.min.css');
AppAsset::addScript($this, 'chat.min.js');

$confg = $this->params['config'];
$key = $confg['ws_key'];
$uid = Yii::$app->user->isGuest ? 0 : Yii::$app->user->id;
$username = '';
if($uid){
   $username = Yii::$app->user->identity->username;
}
$cityName = tools::ip2city(Yii::$app->request->userIP);
?>
<div id="jam_chat_sbox" class="">
    聊天室
</div>
<div id="jam_chat_main" style="display:none;">
    <div id="jam_chat_top">
        <div class="jam_chat_head">
            <div class="title"><span id="jam_chat_online" class="jam_chat_online_off"></span><?=$username?></div>
            <div class="close"><a id="jam_chat_main_close" href="javascript:;">X</a></div>
        </div>
        <div class="chat-choose">
            <ul>
                <li class="cur" data-type="message">群聊</li>
                <li data-type="member">成员（<span id="jam_chat_member_count">0</span>）</li>
            </ul>
        </div>
    </div>
    <div id="jam_chat_content">
        <div id="jam_chat_message">
            <ul id="jam_chat_message_list">

            </ul>
        </div>
        <div id="jam_chat_message_send">
            <textarea id="jam_chat_message_content"></textarea>
            <a href="javascript:;" id="jam_chat_send_btn">发送</a>
        </div>
    </div>
    <div id="jam_chat_member">
        <div id="jam_chat_member_list">
            <ul id="jam_chat_member_list_ul">

            </ul>
        </div>
        <div id="jam_chat_member_tool">
            工具栏，暂时用不到
        </div>
    </div>
</div>
<?php
if($uid!=0) {
    $token = md5(md5($uid) . $key);
    ?>
    <script type="text/javascript">
        window.WS_UID = <?=$uid?>;
        window.WS_USERNAME = '<?=$username?>';
        window.FROM_CITY = '<?=$cityName?>';
        window.WS_HEADURL = '<?=$this->params['defaultHeadImg']?>';
        window.WS_URL = '<?php echo 'ws://'.$confg['ws_host'].':'.$confg['ws_port'].'?uid=' . $uid . '&username=' . urlencode($username) . '&token=' . $token.'&city='.  urlencode($cityName);?>';
    </script>
    <?php
}
?>