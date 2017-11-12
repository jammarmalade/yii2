;
(function () {
    $('#jam_chat_sbox').click(function(){
        $('#jam_chat_main').show();
        $(this).hide();
    })
    $('#jam_chat_main_close').click(function(){
        $('#jam_chat_sbox').show();
        $('#jam_chat_main').hide();
    })
    $('.chat-choose li').click(function(){
        $(this).addClass('cur');
        $(this).siblings().removeClass('cur');
        var type = $(this).attr('data-type');
        if(type=='message'){
            $('#jam_chat_content').show();
            $('#jam_chat_member').hide();
        }else{
            $('#jam_chat_member').show();
            $('#jam_chat_content').hide();
        }
    })
})();
//ws
console.log(window.WS_URL);
var ws = new WebSocket(window.WS_URL);
var connected = false;
ws.onopen = function(event) {
    connected = true;
    console.log('connected server!');
};
ws.onmessage = function(event) {
    var data = event.data;
    data = eval("("+data+")");
    if (data.event == 'sendMsgAll') {
        showMessage(data);
    }
};
ws.onclose = function(event) {
    console.log('Client has closed.\n');
};
$('#jam_chat_send_btn').click(function () {
    if(window.WS_UID==0){
        showMsg('请先登录');
        return false;
    }
    if(!connected){
        console.log('Client is not connected!');
        return false;
    }
    var content = $('#jam_chat_message_content').val();
    if($.trim(content)==''){
        showMsg('请先输入要发送的内容');
        return false;
    }
    var data = {};
    data['uid'] = window.WS_UID;
    data['event'] = 'sendMsgAll';
    data['content'] = content;
    ws.send(JSON.stringify(data));
    $('#jam_chat_message_content').val('');
})
function showMessage(data){
    var html = '';
    if(data['fromUserId']==window.WS_UID){
        html += '<li class="jam-chat-mine">';
    }else{
        html += '<li>';
    }
    html += '<div class="jam-chat-user">';
    html += '<img src="'+window.WS_HEADURL+'">';
    html += '<span>';
    if(data['fromUserId']==window.WS_UID){
        html += '<i>'+getNowFormatDate()+'</i>'+data['username']+' </span>';
    }else{
        html += data['username']+'<i>'+getNowFormatDate()+'</i></span>';
    }
    html += '</div><div class="jam-chat-text">'+data['message']+'</div></li>';
    $('#jam_chat_message_list').append(html);
    $('#jam_chat_message').animate({"scrollTop":$('#jam_chat_message').prop('scrollHeight')},1000);
}
