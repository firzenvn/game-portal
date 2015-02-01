<!-- shoutbox -->
<div class="shout_box">
    <div class="header">
        Chat BOX <div class="close_btn">&nbsp;</div>
    </div>
    <div class="toggle_chat">
        <div class="message_box" id="message_box">
        </div>
        <div class="user_info">
            <input name="shout_message" id="shout_message" placeholder="Chat và nhấn Enter" maxlength="200" />
        </div>
    </div>
</div>
<!-- shoutbox end -->

<script type="text/javascript" src="/lib/chatbox/chat.js"></script>
<script src="/lib/jquery-emotions-master/jquery.emotions.js"></script>
<link rel="stylesheet" href="/lib/jquery-emotions-master/jquery.emotions.fb.css">

<script type="text/javascript">

        // ask user for name with popup prompt
        var name = '{{Auth::user() ? Auth::user()->username : 'Khách_'.rand(1000,9999)}}';

        // default name is 'Guest'
    	if (!name || name === ' ') {
    	   name = "Guest";
    	}

    	var file = '{{$chatbox_name}}';

    	// strip tags
    	name = name.replace(/(<([^>]+)>)/ig,"");

    	// kick off chat
        var chat =  new Chat();
    	$(function() {
    		 chat.getState();

    		 // watch textarea for key presses
             $("#shout_message").keydown(function(event) {

                 var key = event.which;

                 //all keys including return.
                 if (key >= 33) {

                     var maxLength = $(this).attr("maxlength");
                     var length = this.value.length;

                     // don't allow new content if length is maxed out
                     if (length >= maxLength) {
                         event.preventDefault();
                     }
                  }
    		 																																																});
    		 // watch textarea for release of key press
    		 $('#shout_message').keyup(function(e) {

    			  if (e.keyCode == 13) {

                    var text = $(this).val();
    				var maxLength = $(this).attr("maxlength");
                    var length = text.length;

                    // send
                    if (length <= maxLength + 1) {

    			        chat.send(text, name);
    			        $(this).val("");

                    } else {

    					$(this).val(text.substring(0, maxLength));

    				}


    			  }
             });

    	});

    	$(function(){
    	    setInterval(function(){
    	        chat.update();
    	    }, 1000);

    	    //toggle hide/show shout box
            $(".shout_box .header").click(function (e) {
                //get CSS display state of .toggle_chat element
                var toggleState = $('.toggle_chat').css('display');

                //toggle show/hide chat box
                $('.toggle_chat').slideToggle();

                //use toggleState var to change close/open icon image
                if(toggleState == 'block')
                {
                    $(".header div").attr('class', 'open_btn');
                }else{
                    $(".header div").attr('class', 'close_btn');
                }
            });
    	});

    </script>
