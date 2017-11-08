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
            $('#jam_chat_message').show();
            $('#jam_chat_member').hide();
        }else{
            $('#jam_chat_member').show();
            $('#jam_chat_message').hide();
        }
    })
})();
