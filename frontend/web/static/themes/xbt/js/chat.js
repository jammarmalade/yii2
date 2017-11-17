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
var ws;
var connected = false;
var reconnectFlag = false;
createWebSocket();
function createWebSocket() {
    try {
        ws = new WebSocket(window.WS_URL);
        initWebSocket();
    } catch (e) {
        console.log('reconnect ws!!!');
        reconnect(window.WS_URL);
    }
}
function initWebSocket(){
    ws.onopen = function() {
        //上线状态
        $('#jam_chat_online').removeClass('jam_chat_online_off');
        console.log('Client connected.\n');
        //开始心跳检测
        heartCheck.reset().start();
        connected = true;
        //获取成员列表
        var data = {};
        data['uid'] = window.WS_UID;
        data['event'] = 'getMemberList';
        ws.send(JSON.stringify(data));
    };
    ws.onmessage = function(event) {
        //收到消息，重置心跳检测
        heartCheck.reset().start();
        var data = event.data;
        data = eval("("+data+")");
        if (data.event == 'sendMsgAll') {//接收群体消息
            showMessage(data);
        }else if(data.event =='addMember'){//上线通知
            addMember(data);
        }else if(data.event =='getMemberList'){//获取在线用户列表
            addMemberList(data.memberList);
        }else if(data.event =='deleteMember'){//下线通知
            $('#member_'+data.uid).remove();
        }else if(data.event =='uncheck'){
            console.log('stop check.\n');
            //停止重连
            connected = false;//标记为没有链接
            reconnectFlag = true;//标记为重连状态，这样 onclose() 在执行 reconnect() 的时候就不会去重连
            heartCheck.reset();
        }
    };
    ws.onclose = function(event) {
        //下线状态
        $('#jam_chat_online').addClass('jam_chat_online_off');
        console.log('Client has closed.\n');
        reconnect();
    };
    ws.onerror = function () {
        //下线状态
        $('#jam_chat_online').addClass('jam_chat_online_off');
        console.log('Client error.\n');
        reconnect();
    };
}

function reconnect(url) {
    if(reconnectFlag){
        return false;
    }
    reconnectFlag = true;
    //没连接上会一直重连，设置延迟避免请求过多
    setTimeout(function () {
        createWebSocket(url);
        reconnectFlag = false;
    }, 2000);
}
//心跳检测
var heartCheck = {
    timeout: 30000,//30秒
    timeoutObj: null,
    serverTimeoutObj: null,
    reset: function(){
        clearTimeout(this.timeoutObj);
        clearTimeout(this.serverTimeoutObj);
        return this;
    },
    start: function(){
        var self = this;
        this.timeoutObj = setTimeout(function(){
            //发送一个检查的操作（event）
            var data = {};
            data['event'] = 'keepalive';
            ws.send(JSON.stringify(data));
            self.serverTimeoutObj = setTimeout(function(){//如果超过一定时间还没重置，说明后端主动断开了
                ws.close();//直接 ws.close(),在 onclose() 中已有 reconnect()
            }, self.timeout);
        }, this.timeout);
    }
}
$('#jam_chat_send_btn').click(function () {
    if(window.WS_UID==0){
        showMsg('请先登录');
        return false;
    }
    if(!connected){
        showMsg('未链接到服务器！刷新页面试试~');
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
function addMember(data){
    var html = '';
    html += '<li id="member_'+data['uid']+'"><img src="'+window.WS_HEADURL+'"><span>'+data['username']+'</span><p>'+data['city']+'</p></li>';
    $('#jam_chat_member_list_ul').append(html);
}
function addMemberList(data){
    var html = '',tmpData=[];
    for(var k in data){
        tmpData = data[k];
        html += '<li id="member_'+tmpData['uid']+'"><img src="'+window.WS_HEADURL+'"><span>'+tmpData['username']+'</span><p>'+tmpData['city']+'</p></li>';
    }
    $('#jam_chat_member_list_ul').append(html);
}
