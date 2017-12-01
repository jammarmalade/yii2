;
(function () {
    $('.audio-pople').click(function(){
        $('.audio-pople').removeClass('audio-pople-active');
        $(this).addClass('audio-pople-active');
    })
    var ajaxSending = false;
    //合成
    $('#create').click(function(){
        var content = $('#content').val();
        if($.trim(content)==''){
            showMsg('请输入合成内容');
            return false;
        }
        var spd = $('#spd').val();
        var pit = $('#pit').val();
        var vol = $('#vol').val();
        var per = $('.audio-pople-active').attr('data-id');
        var text = $('.audio-pople-active').text();
        if(ajaxSending){
            return false;
        }
        $(this).text('生成中...');
        var _this = $(this);
        ajaxSending = true;
        //提交
        $.post(window.URL_AUDIO,{'content': content,'spd': spd,'pit': pit,'vol': vol,'per': per},function(d){
            if(d.status){
                var data = d.data;
                var html = '<div class="audio-item"><span class="audio-item-text">'+text+' </span>';
                html += '<audio src="'+d.msg+'" controls="controls">您的浏览器不支持 audio 标签。</audio>';
                html += '<div class="audio-item-content">内容：'+data['content']+'</div><div class="audio-item-time">生成时间：'+data['create_time']+'</div>';
                html += '</div>';
                $('#audio_list').prepend(html);
            }else{
                showMsg(d.msg);
            }
            _this.text('合成语音');
            ajaxSending = false;
        },'json')
    })
})();
