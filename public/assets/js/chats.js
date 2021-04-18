$(function () {
    var id_item = '';
    /**
     * Some examples of how to use features.
     *
     **/
   
    var ChatosExamle = {
        Message: {
            add: function (message, type, connected, currentdate) {
                var chat_body = $('.layout .content .chat .chat-body');
                if (chat_body.length > 0) {

                    type = type ? type : '';
                    message = message ? message : 'message';

                    var check = "";
                    if(connected == 1){
                        check = '<i class="ti-double-check"></i>';
                    }else{
                        check = '<i class="ti-check"></i>';
                    }

                    $('.layout .content .chat .chat-body .messages').append('<div class="message-item ' + type + '"><div class="message-content">' + message + '</div><div class="message-action">' + currentdate + check + '</div></div>');

                    chat_body.scrollTop(chat_body.get(0).scrollHeight, -1).niceScroll({
                        cursorcolor: 'rgba(66, 66, 66, 0.20)',
                        cursorwidth: "4px",
                        cursorborder: '0px'
                    }).resize();
                }
            }
        }
    };

    setInterval(function () {

        $.ajax({
            url: 'messages/list',
            type: 'post',
            data: '' ,
            success: function (reponse) {
                $('#list-message').html(reponse);

                if(id_item !== ''){
                    $('.user_chat').removeClass('open-chat');
                    $("#"+id_item).addClass('open-chat');
                }
                    
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    }, 1000);

    setInterval(function () {
        var id = $('#idd').val();
        if(id){
            $.ajax({
                url: 'messages/single',
                type: 'post',
                data: { 'id': id } ,
                success: function (reponse) {
                    $('#cnvtn-1').html(reponse);
                    var chat_body = $('.layout .content .chat .chat-body');
                    chat_body.scrollTop(chat_body.get(0).scrollHeight, -1).niceScroll({
                        cursorcolor: 'rgba(66, 66, 66, 0.20)',
                        cursorwidth: "4px",
                        cursorborder: '0px'
                    }).resize();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }
    }, 3000);
    
    setTimeout(function () {
        ChatosExamle.Message.add();
    }, 100);

    $(document).on('submit', '.layout .content .chat .chat-footer form', function (e) {
        e.preventDefault();

        var input = $(this).find('input[type=text]');
        var id = $(this).children('#idd').val();
        var message = input.val();

        message = $.trim(message);

        if (message) {
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: {"id": id, 'message': message} ,
                dataType: "json",
                success: function (data) {
                    var d = jQuery.parseJSON(JSON.stringify(data));
                    ChatosExamle.Message.add(message, 'outgoing-message', d.connected, d.date_m);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });

            input.val('');
        } else {
            input.focus();
        }
    });

    $(document).on('click', '.layout .content .sidebar-group .sidebar .list-group-item', function () {
        if (jQuery.browser.mobile) {
            $(this).closest('.sidebar-group').removeClass('mobile-open');
        }
    });
    $(document).on('keyup', '#search', function () {
        var val = $(this).val();
        if (val.length > 2) {
            $.ajax({
                url: './search',
                type: "post",   
                data: {"search": val} ,
                success: function (html) {
                    $('#loadfriends').html(html);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }else if (val.length == 0) {
            $.ajax({
                url: './search',
                type: "post",   
                data: {} ,
                success: function (html) {
                    $('#loadfriends').html(html);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }
    });
    $(document).on('click', '.user_chat', function () {
         
        var id = this.id;
        var item = $(this);

        id_item = id;

        if (id) {
            $.ajax({
                url: './chat',
                type: "post",
                data: {"id": id} ,
                success: function (response) {
                    $('.user_chat').removeClass('open-chat');
                    item.addClass('open-chat');
                    $("#chat").html(response);
                    var chat_body = $('.layout .content .chat .chat-body');
                    chat_body.scrollTop(chat_body.get(0).scrollHeight, -1).niceScroll({
                        cursorcolor: 'rgba(66, 66, 66, 0.20)',
                        cursorwidth: "4px",
                        cursorborder: '0px'
                    }).resize();
                    item.find('.new-message-count').remove();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }
    });

    
});