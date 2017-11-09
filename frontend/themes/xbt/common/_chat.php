<?php
use frontend\assets\AppAsset;

AppAsset::addCss($this, 'chat.css');
AppAsset::addScript($this, 'chat.js');
?>
<div id="jam_chat_sbox" style="display:none;">
    在线人数 <span>666</span>
</div>
<div id="jam_chat_main">
    <div id="jam_chat_top">
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
    </div>
    <div id="jam_chat_content">
        <div id="jam_chat_message">
            <ul>
                <li>
                    <div class="jam-chat-user">
                        <img src="http://tva1.sinaimg.cn/crop.0.23.1242.1242.180/8693225ajw8fbimjimpjwj20yi0zs77l.jpg">
                        <span>jam00 <i>2017-11-10 00:23:18</i></span>
                    </div>
                    <div class="jam-chat-text">测试一下，哈哈哈哈~~~！黑河呵呵，傻逼打死你打算年底阿森纳多看哈 那块思念对方那可</div>
                </li>
                <li class="jam-chat-mine">
                    <div class="jam-chat-user">
                        <img src="http://tva1.sinaimg.cn/crop.0.23.1242.1242.180/8693225ajw8fbimjimpjwj20yi0zs77l.jpg">
                        <span><i>2017-11-10 00:23:18</i>admin </span>
                    </div>
                    <div class="jam-chat-text">测试一下，哈哈哈哈~~~！黑河呵呵，傻逼打死你打算年底阿森纳多看哈 那块思念对方那可</div>
                </li>
                <li>
                    <div class="jam-chat-user">
                        <img src="http://tva1.sinaimg.cn/crop.0.23.1242.1242.180/8693225ajw8fbimjimpjwj20yi0zs77l.jpg">
                        <span>jam00 <i>2017-11-10 00:23:18</i></span>
                    </div>
                    <div class="jam-chat-text">测试一下，哈哈哈哈~~~！黑河呵呵，傻逼打死你打算年底阿森纳多看哈 那块思念对方那可</div>
                </li>
            </ul>
        </div>
        <div id="jam_chat_message_send">
            <textarea id="jam_chat_message_content"></textarea>
            <a href="javascript:;" id="jam_chat_send_btn">发送</a>
        </div>
    </div>
    <div id="jam_chat_member" style="display: none;">
        在线成员
    </div>
</div>