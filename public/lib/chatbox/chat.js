/* 
 Created by: Kenrick Beckett

 Name: Chat Engine
 */

var instanse = false;
var state;
var mes;


function Chat () {
    this.update = updateChat;
    this.send = sendChat;
    this.getState = getStateOfChat;
    this.first = getFirstChat;
}

//gets the state of the chat
function getStateOfChat(){
    if(!instanse){
        instanse = true;
        $.post('/chat/state',{
                file: file
            }
            ,function(data){
                state = data.state;
                instanse = false;
                getFirstChat();
            },'json');
    }
}

//get First Chat
function getFirstChat(){
    $.post('/chat/first',{
            state: state, file: file
        }
        ,function(data){
            for (var i = 0; i < data.text.length; i++) {
                $('#message_box').append($("<div class='shout_msg'>"+ data.text[i] +"</div>"));
            }
            $(".message").emotions();
            document.getElementById('message_box').scrollTop = document.getElementById('message_box').scrollHeight;

        },'json');
}

//Updates the chat
function updateChat(){
    if(!instanse){
        instanse = true;
        $.post('/chat/update',{
                state: state, file: file
            }
            ,function(data){
                if(data.text){
                    for (var i = 0; i < data.text.length; i++) {
                        $('#message_box').append($("<div class='shout_msg'>"+ data.text[i] +"</div>"));
                    }
                    $(".message").emotions();
                    document.getElementById('message_box').scrollTop = document.getElementById('message_box').scrollHeight;
                }
                instanse = false;
                state = data.state;

            },'json');
    }
    else {
        setTimeout(updateChat, 1500);
    }
}

//send the message
function sendChat(message, nickname)
{
    updateChat();
    $.post('/chat/send',{
            message: message, nickname: nickname, file: file
        }
        ,function(data){
            updateChat();
        },'json');
}
