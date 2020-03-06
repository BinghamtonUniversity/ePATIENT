
chat_init = function() {
    // Handle Message Posting
    var myappcontext = this
    $(".chat-textarea").keypress(function (e) {
        if(e.which == 13) {
            var msg_text = $(this).val()
            $(this).val('')
            myappcontext.app.post('msg_submit',{team_id:myappcontext.data.team_id,message:msg_text},function(response){
                chat_add_messages.call(myappcontext,[response])
                myappcontext.app.update();

            })
            e.preventDefault();
        }
    });
    $('#chat_collapse_1').on('shown.bs.collapse', function () {
        $('.chat-messages').scrollTop($('.chat-messages')[0].scrollHeight);
    })
}

chat_add_messages = function(messages){
    if (messages.length > 0) {
        for(var i=0;i<messages.length;i++) {
            if (this.data.user.unique_id === messages[i].user.unique_id) {
                messages[i].me = true;
            }
        }
        this.data.messages = _.unionBy(this.data.messages,messages,'id');
        // this.app.update();
        $('.chat-messages').scrollTop($('.chat-messages')[0].scrollHeight);
    }
}