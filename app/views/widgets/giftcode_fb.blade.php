<section id="like_share_to_view">

</section>

<script type="text/javascript">
    var cookiename = '{{$name}}';
    var cookievalue = getCookie(cookiename );
    $(function(){
            if(cookievalue == ""){
                showLike();
            } else {
                showCode();
            }
    });


function showLike(){
    $("#like_share_to_view").html(
        '<p>Bạn cần bấm <span>Like</span> và <span>Share</span> Facebook để nhận Giftcode. Nếu bạn đã bấm Like vui lòng Unlike rồi Like lại</p>'+
        '<fb:like href="{{$linkFB}}" send="false" layout="standard" width="450" show_faces="true" colorscheme="light"></fb:like>'+
        '<script type="text/javascript">'+
        'FB.Event.subscribe("edge.create", function(href){'+
        'if(href == "{{$linkFB}}"){'+
        'setCookie("'+cookiename+'", true, 8640000);'+
        'setTimeout(function(){showCode()},8000);'+
        '}'+
        '});'+
        '</'+'script>'
    );
}

function showCode(){
    $("#like_share_to_view").html(
    '<h4>{{$giftcode_type->name}}</h4>' +
    @if(!Auth::user()->giftcodes()->where("giftcode_type_id",$giftcode_type->id)->first())
     '<input type="text" id="txtGiftcode" value="" disabled />' +
     '<a id="getcode" class="getcode" onclick="getCode()">Nhận code</a>'
     @else
'     <input type="text" id="txtGiftcode"  value="{{Auth::user()->giftcodes()->where("giftcode_type_id",$giftcode_type->id)->first()->code}}" disabled />' +
     '<a id="getcode" class="danhan" title="Bạn đã nhận giftcode này">Đã nhận</a>'
     @endif
      );
}

function setCookie(cname, cvalue, exsecs) {
    var d = new Date();
    d.setTime(d.getTime() + (exsecs*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
    }
    return "";
}

function getCode(){
    $.post('/gift-code',{
            giftcode_type:{{$giftcode_type->id}}
        }
        ,function(result){
            console.log(result.success);
            if(result.success){
                $('#txtGiftcode').val(result.code);
                $('a#getcode').removeClass('getcode').addClass('danhan').html('Đã nhận');
                $('a#getcode').attr({
                    'title':'Bạn đã nhận code này',
                    'href':'javascript:;'
                });
            }else{
                alert(result.msg);
            }

        },'json');
}


</script>

