

<div class="form-group">
    <div class="col-lg-10">
        <h3>Thống kê sản lượng theo game và thời gian</h3>
    </div>

</div>

<div style="clear: both"></div>
<div style="height: 20px"></div>


{{-- The error / success messaging partial --}}
@include('includes.messaging')

{{Form::open(array('url'=>'/'.$urlSegment.'/reports/sum-by-game', 'method'=>'get', 'role'=>'form'))}}
<div class="form-group">
    <div class="row">
        <div class="col-xs-2">
            <label for="">Nạp vào game:</label>
        </div>
        <div class="col-xs-2">
            {{Form::text('start_date',Input::get('start_date'),array('class'=>'form-control input-sm','placeholder'=>'Từ:','id'=>'start_date'))}}
        </div>
        <div class="col-xs-2">
            {{Form::text('end_date',Input::get('end_date'),array('class'=>'form-control input-sm','placeholder'=>'Đến:', 'id'=>'end_date'))}}
        </div>


        <div class="col-xs-2">
            <select class="form-control" name="allGame">
                <?php
                foreach ($allGames as $aGame) {

                     echo '  <option value="'.$aGame->id.'">'.$aGame->name.'</option>';
                }

                ?>
            </select>
        </div>
        <div class="col-xs-1">
            {{Form::button('Tìm', array('class'=>'btn btn-success btn-sm', 'type'=>'submit'))}}
<!--            <a href="javascript:loadReport();" class="btn btn-success btn-sm">Tìm</a>-->
        </div>
    </div>
</div>

{{Form::close()}}
<div style="background-color: #008080; padding: 10px; border-radius: 4px 4px 0px 0px ;color: #fff; " >Tổng tiền</div>
<div style="padding: 10px; border: 1px solid #ccc; border-radius: 0px 0px 4px 4px;margin-bottom: 10px ">
    <table class="table table-condensed">
        <thead>
        <tr>
            <th>STT</th>
            <th>Tên game</th>
            <th>Tổng tiền</th>
            <th>Thời gian</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $tmpTong = array();
        foreach ($allTxns as $aTxn) {
            if(!isset($tmpTong[$aTxn->game_id]))
                $tmpTong[$aTxn->game_id]=array();
            $tmpTong[$aTxn->game_id] = array('id'=>$aTxn->game_id, 'name'=>$aTxn->game_name, 'total'=>$aTxn->pay_amount*100, 'date'=>$aTxn->start_date.' to '.$aTxn->end_date);
        }

        /*foreach ($allCardTxns as $aTxn) {
            $tmpTong[$aTxn->game_id]['total'] = $tmpTong[$aTxn->game_id]['total'] + $aTxn->pay_amount*100;
        }*/

        foreach ($allSohaTxns as $aTxn) {
            if(!isset($tmpTong[$aTxn->game_id]))
                $tmpTong[$aTxn->game_id]=array('id'=>$aTxn->game_id, 'name'=>$aTxn->game_name, 'total'=>0,'date'=>$aTxn->start_date.' to '.$aTxn->end_date);
            $tmpTong[$aTxn->game_id]['total'] = $tmpTong[$aTxn->game_id]['total'] + $aTxn->pay_amount*1000;
        }

        foreach ($allZingTxns as $aTxn) {
            if(!isset($tmpTong[$aTxn->game_id]))
                $tmpTong[$aTxn->game_id]=array('id'=>$aTxn->game_id, 'name'=>$aTxn->game_name, 'total'=>0,'date'=>$aTxn->start_date.' to '.$aTxn->end_date);
            $tmpTong[$aTxn->game_id]['total'] = $tmpTong[$aTxn->game_id]['total'] + $aTxn->pay_amount*100;
        }

        ?>
        @foreach($tmpTong as $item)
        <tr>
            <td>{{ $item['id'] }}</td>
            <td>{{$item['name']}}</td>
            <td>{{ number_format($item['total']) }}</td>
            <td>{{ $item['date']}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>




<div style="background-color: #008080; padding: 10px; border-radius: 4px 4px 0px 0px ;color: #fff; " >Nạp từ Xu</div>
<div style="padding: 10px; border: 1px solid #ccc; border-radius: 0px 0px 4px 4px;margin-bottom: 10px ">
<table class="table table-condensed">
    <thead>
    <tr>
        <th>STT</th>
        <th>Tên game</th>
        <th>Tổng xu</th>
        <th>Tổng tiền</th>
        <th>Tổng game</th>
        <th>Thời gian</th>
    </tr>
    </thead>
    <tbody>

    @foreach($allTxns as $item)
    <tr>
        <td>{{ $item->game_id }}</td>
        <td>{{$item->game_name}}</td>
        <td>{{ number_format($item->pay_amount) }}</td>
        <td>{{ number_format($item->pay_amount*100) }}</td>
        <td>{{ number_format($item->game_amount) }}</td>
        <td>{{ $item->start_date }} to {{ $item->end_date }}</td>
    </tr>
    @endforeach
    </tbody>
</table>
</div>


<div style="background-color: #008080; padding: 10px; border-radius: 4px 4px 0px 0px ;color: #fff" >Nạp từ Soha</div>
<div style="padding: 10px; border: 1px solid #ccc; border-radius: 0px 0px 4px 4px; margin-bottom: 10px">
    <table class="table table-condensed">
        <thead>
        <tr>
            <th>STT</th>
            <th>Tên game</th>
            <th>Scoin</th>
            <th>Tổng tiền</th>
            <th>Tổng game</th>
            <th>Thời gian</th>
        </tr>
        </thead>
        <tbody>

        @foreach($allSohaTxns as $item)
        <tr>
            <td>{{ $item->game_id }}</td>
            <td>{{$item->game_name}}</td>
            <td>{{ number_format($item->pay_amount) }}</td>
            <td>{{ number_format($item->pay_amount*1000) }}</td>
            <td>{{ number_format($item->game_amount) }}</td>
            <td>{{ $item->start_date }} to {{ $item->end_date }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>


<div style="background-color: #008080; padding: 10px; border-radius: 4px 4px 0px 0px ;color: #fff" >Nạp từ Zing</div>
<div style="padding: 10px; border: 1px solid #ccc; border-radius: 0px 0px 4px 4px; margin-bottom: 10px">
    <table class="table table-condensed">
        <thead>
        <tr>
            <th>STT</th>
            <th>Tên game</th>
            <th>Zing xu</th>
            <th>Tổng tiền</th>
            <th>Tổng game</th>
            <th>Thời gian</th>
        </tr>
        </thead>
        <tbody>

        @foreach($allZingTxns as $item)
        <tr>
            <td>{{ $item->game_id }}</td>
            <td>{{$item->game_name}}</td>
            <td>{{ number_format($item->pay_amount) }}</td>
            <td>{{ number_format($item->pay_amount*100) }}</td>
            <td>{{ number_format($item->game_amount) }}</td>
            <td>{{ $item->start_date }} to {{ $item->end_date }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="text-center">

</div>

<script type="text/javascript">

</script>