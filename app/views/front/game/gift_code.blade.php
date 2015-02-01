<section class="giftcode">
    <section class="giftcode-nav">
		<h2>Giftcode</h2>
    </section>
    <section class="breadcrumb text-right">
        <a href="/">Trang chủ</a> &raquo; Giftcode
    </section>
    <section class="giftcode-content">
        <section class="giftcode-guide">
            <a target="_blank" href="{{isset($giftcode_types->first()->input_guide_link)?$giftcode_types->first()->input_guide_link:'#'}}">Cách sử dụng Giftcode</a>
        </section>
        <ul class="giftcode-list list-unstyled">
            @foreach($giftcode_types as $giftcode_type)
            <li>
                <h4>{{$giftcode_type->name}}</h4>
                <section>
                    @if(!Auth::user()->giftcodes()->where('giftcode_type_id',$giftcode_type->id)->first())
                    <a href="javascript:getCode({{$giftcode_type->id}});" id="getcode{{$giftcode_type->id}}" class="getcode" title="Bạn có thể nhận">Nhận code</a>
                    <input type="text" id="giftcode{{$giftcode_type->id}}" class="form-control" disabled />
                    @else
                    <a href="javascript:;" class="danhan" title="Bạn đã nhận code này">Đã nhận</a>
                    <input type="text" id="giftcode{{$giftcode_type->id}}" class="form-control" value="{{Auth::user()->giftcodes()->where('giftcode_type_id',$giftcode_type->id)->first()->code}}" disabled />
                    @endif
                    <p>Chi tiết quà tặng cho code này xem <a target="_blank" href="{{isset($giftcode_type->gift_link)?$giftcode_type->gift_link:'#'}}">Tại đây</a></p>
                </section>
            </li>
            @endforeach
        </ul>
    </section>

    <script>
        function getCode(id){
            $.post('/gift-code',{
                    giftcode_type:id
                }
                ,function(result){
                    console.log(result.success);
                    if(result.success){
                        $('input#giftcode'+id).val(result.code);
                        $('a#getcode'+id).removeClass('getcode').addClass('danhan').html('Đã nhận');
                        $('a#getcode'+id).attr({
                            'title':'Bạn đã nhận code này',
                            'href':'javascript:;'
                        });
                    }else{
                        alert(result.msg);
                    }

                },'json');
        }



    </script>
</section>