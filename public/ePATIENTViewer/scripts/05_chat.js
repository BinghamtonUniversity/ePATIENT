chat_init = function({team_id, team_name}={}) {
    this.data.chat = {};
    this.data.messages = [];
    this.data.chat.team_name = team_name;
    this.app.update();
    // Determine if message is me or someone else
    process_messages = function(messages) {
        for(var i=0;i<messages.length;i++) {
            if (this.data.user.unique_id === messages[i].user.unique_id) {
                messages[i].me = true;
            }
        }
        return messages;
    }
    
    // Fetch All Messages
    fetch_messages = function(messages){
        this.data.messages = this.data.messages.concat(process_messages.call(this,messages));
        this.app.update();
        $('.chat-messages').scrollTop($('.chat-messages')[0].scrollHeight);
    }
    
    // Handle Message Posting
    var myappcontext = this
    $(".chat-textarea").keypress(function (e) {
        if(e.which == 13) {
            var msg_text = $(this).val()
            $(this).val('')
            myappcontext.app.post('msg_submit',{team_id:team_id,message:msg_text,unique_id:myappcontext.data.user.unique_id},function(response){
                fetch_messages.call(myappcontext,[response])
            })
            e.preventDefault();
        }
    });
    
    // On Init, fetch all messages
    // fetch_messages.call(this)
    // setInterval(fetch_messages.bind(this), 10000);
}