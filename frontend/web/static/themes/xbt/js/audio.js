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
        ajaxSending = true;
        //提交
        $.post(window.URL_AUDIO,{'content': content,'spd': spd,'pit': pit,'vol': vol,'per': per},function(d){
            if(d.status){
                var html = '<div class="audio-item">'+text+' <audio src="'+d.msg+'" controls="controls">您的浏览器不支持 audio 标签。</audio></div>';
                $('#audio_list').append(html);
            }else{
                showMsg(d.msg);
            }
            $(this).text('合成语音');
            ajaxSending = false;
        },'json')
    })
})();
