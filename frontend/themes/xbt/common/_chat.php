<?php
use frontend\assets\AppAsset;

AppAsset::addCss($this, 'chat.css');
AppAsset::addScript($this, 'chat.js');
?>
<div id="jam_chat_sbox" style="display:none;">
    在线人数 <span>666</span>
</div>
<div id="jam_chat_main">
    <div class="jam_chat_head">
        <div class="title">在线人数 666</div>
        <div class="close"><a id="jam_chat_main_close" href="javascript:;">X</a></div>
    </div>
    <div class="chat-choose">
        <ul>
            <li class="cur" data-type="message">群聊</li>
            <li data-type="member">成员</li>
        </ul>
    </div>
    <div id="jam_chat_message">
        消息区域
    </div>
    <div id="jam_chat_member" style="display: none;">
        在线成员
    </div>
</div>