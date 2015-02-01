<h3><a href="/{{$urlSegment}}/league">Quản lý giải đấu</a> &raquo; Sửa giải đấu {{$item->name}}</h3>

@include('includes.messaging')
<div id="ajaxMsg"></div>
{{ Form::open( array( 'url'=>'#' , 'class'=>'form-horizontal form-top-margin' , 'role'=>'form' ) ) }}
{{-- Title --}}
<div class="form-group">
    {{ Form::label( "txtName" , 'Tên (*)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtName" , Input::old( "name", $item->name ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập tên' ) ) }}
    </div>
</div>

{{-- Description --}}
<div class="form-group">
    {{ Form::label( "txtDescription" , 'Mô tả' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::textarea( "txtDescription" , Input::old( "description", $item->description ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập mô tả', 'style'=>'height:60px' ) ) }}
    </div>
</div>

{{-- Games --}}
<div class="form-group">
    {{ Form::label( "cboGame" , 'Game' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{Form::select('cboGame', $allGame, $item->game_id, array('class'=>'selectpicker show-tick'))}}
    </div>
</div>

<div class="form-group">
    {{ Form::label( "start_date" , 'Ngày bắt đầu' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-2">
        {{Form::text('start_date',Input::get('start_date', $item->start_date),array('class'=>'form-control input-sm','placeholder'=>'Từ:','id'=>'start_date'))}}
    </div>
</div>
<div class="form-group">
    {{ Form::label( "end_date" , 'Ngày kết thúc' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-2">
        {{Form::text('end_date',Input::get('end_date', $item->end_date),array('class'=>'form-control input-sm','placeholder'=>'Đến:','id'=>'end_date'))}}
    </div>
</div>

<div class="form-group">
    {{ Form::label( "chkActive" , 'Active' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10" >
        <input type="checkbox" id="chkActive"  {{$item->active==1?'checked':''}} style="margin: 10 0 0 0px" />
    </div>
</div>
<div class="form-group">
    <div class="col-lg-12 bg-info" >
        <div class="text-info" style="padding:7px; font-size: 1.2em; ">
            <span><strong>Danh sách người tham gia giải</strong></span>&nbsp;&nbsp;&nbsp;
            <a class="btn btn-sm btn-danger" href="javascript:addUser()"><span class="glyphicon glyphicon-plus"></span></a>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-lg-12 bg-info" >

        <table class="table table-condensed" id="tblUser">
            <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Nhóm</th>
                <th>Điểm</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($allUsers as $user) {

                echo '<tr id="leagueUserRow'.$user->id.'">
                            <td>'.$user->id.'</td>
                            <td>'.$user->username.'</td>
                            <td>'.$user->level_range.'</td>
                            <td>'. $user->point.'</td>
                            <td>
                                <div class="pull-right">
                                    <a href="javascript:editUser('.$user->id.')" class="btn btn-sm btn-primary">Sửa</a>
                                    <a href="javascript:deleteUser('.$user->id.')" class="btn btn-sm btn-danger">Xóa</a>
                                </div>
                            </td>
                       </tr>';
            }

            ?>
            </tbody>
        </table>
    </div>
</div>


<div class="form-group">
    <div class="col-lg-12 bg-info" >
        <div class="text-info" style="padding:7px; font-size: 1.2em; ">
            <span><strong>Danh sách trận đấu</strong></span>&nbsp;&nbsp;&nbsp;
            <a class="btn btn-sm btn-danger" href="javascript:addMatch()"><span class="glyphicon glyphicon-plus"></span></a>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-lg-12 bg-info" >

        <table class="table table-condensed" id="tblMatch">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nhóm</th>
                <th>Vòng đấu</th>
                <th>Tên người chơi 1</th>
                <th>Tên người chơi 2</th>
                <th>Kết quả</th>
                <th>Video</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($allMatch as $match) {

                echo '<tr id="matchRow'.$match->id.'">
                            <td>'.$match->id.'</td>
                            <td>'.$match->range_level.'</td>
                            <td>'.Config::get('common.game_leagues_round.'.$game->subdomain.'.'.$match->round).'</td>
                            <td>'.$match->first_username.'</td>
                            <td>'.$match->second_username.'</td>
                            <td>'.$match->getLiteralResult().'</td>
                            <td>'.$match->video_key.'</td>
                            <td>
                                <div class="pull-right">
                                    <a href="javascript:deleteMatch('.$match->id.')" class="btn btn-sm btn-danger">Xóa</a>
                                </div>
                            </td>
                       </tr>';
            }

            ?>
            </tbody>
        </table>
    </div>
</div>

<a class="btn btn-large btn-primary pull-right" href="javascript:save()">Lưu</a>
{{ Form::close() }}

<div id="wndAddUser"></div>

<script type="text/x-kendo-template" id="addUserTemplate">
    <div id="add-user-container">
        <dl>
            <dt style="padding: 5px"><div style="width: 70px; float:left"> Tên(*):</div>
            <input  id='txtUserId'  name="txtUserId" type="hidden" value='#= id #'>
            <input  id='txtUserName'  name="txtUserName" class="k-textbox"  required  value='#= username #'>
            </dt>
            <dt style="padding: 5px"><div style="width: 70px;float:left"> Nhóm(*):</div>
                <select id="cboLevelRange" value='#= level_range #'>
                    <?php
                    foreach ($allLevelRange as $key => $val) {
                        echo('<option value="'.$val.'">'.$val.'</option>');
                    }
                    ?>
                </select>
            </dt>


            <dt style="padding: 5px">
                <span style="color:red" id="spnStatus"></span>
            </dt>
        </dl>
        <div style="padding: 10px 0px" >
            <a id="btnSaveUser" class="k-button k-button-icontext k-grid-update" >
                <span class="k-icon k-update"></span>
                Lưu
            </a>
            <a id="btnClose" class="k-button k-button-icontext k-grid-cancel">
                <span class="k-icon k-cancel"></span>
                Cancel
            </a>
        </div>
    </div>
</script>



<div id="wndAddMatch"></div>

<script type="text/x-kendo-template" id="addMatchTemplate">
    <div id="add-match-container">
        <dl>
            <dt style="padding: 5px"><div style="width: 120px;float:left"> Nhóm(*):</div>
            <select id="cboMatchLevelRange" >
                    <?php
                    foreach ($allLevelRange as $key => $val) {
                        echo('<option value="'.$val.'">'.$val.'</option>');
                    }
                    ?>
                </select>
            </dt>

            <dt style="padding: 5px"><div style="width: 120px;float:left"> Vòng đấu(*):</div>
            <select id="cboMatchRound" >
                    <option value="1">Vòng 1/8</option>
                    <option value="2">Vòng tứ kết</option>
                    <option value="4">Vòng bán kết</option>
                    <option value="8">Vòng chung kết</option>
                </select>
            </dt>

            <dt style="padding: 5px"><div style="width: 120px;float:left"> Người đấu 1:</div>
            <select id="cboFirstUser" >

            </select>
            </dt>
            <dt style="padding: 5px"><div style="width: 120px;float:left"> Người đấu 2:</div>
            <select id="cboSecondUser" >

            </select>
            </dt>

            </dt>
            <dt style="padding: 5px"><div style="width: 120px;float:left"> Kết quả:</div>
            <select id="cboResult" >
                <option value="1">Người đấu 1 thắng</option>
                <option value="2">Người đấu 2 thắng</option>
            </select>
            </dt>




            <dt style="padding: 5px"><div style="width: 120px; float:left"> Video:</div>
            <input  id='txtMatchId'  name="txtMatchId" type="hidden" value='#= id #'>
            <input  id='txtVideo'  name="txtVideo" class="k-textbox"   value='#= video #'>
            </dt>



            <dt style="padding: 5px">
                <span style="color:red" id="spnMatchStatus"></span>
            </dt>
        </dl>
        <div style="padding: 10px 0px" >
            <a id="btnSaveMatch" class="k-button k-button-icontext k-grid-update" >
                <span class="k-icon k-update"></span>
                Lưu
            </a>
            <a id="btnCloseMatch" class="k-button k-button-icontext k-grid-cancel">
                <span class="k-icon k-cancel"></span>
                Cancel
            </a>
        </div>
    </div>
</script>


<script language="javascript" type="text/javascript">
    $(function() {
        $('#txtName').focus();
    });
    $(document).ready(function(){
        $('.selectpicker').selectpicker();
    })



    function save(){
        $.blockUI({ message: 'Vui lòng chờ' });
        name = $('#txtName').val();
        description = $('#txtDescription').val();

        active = $('#chkActive').is(':checked')?1:0;
        game = $('#cboGame').val();
        start_date = $('#start_date').val();
        end_date = $('#end_date').val();


        $.post('/admin/league/edit/{{$item->id}}',{
                name:name,  active:active,end_date:end_date,
                game:game, description:description, start_date:start_date
            }
            ,function(result){

                $.unblockUI();
                $('#ajaxMsg').html(result.msg);
                if(result.success){

                    window.location.assign('/admin/league')
                }else{

                }

            },'json');
    }
    


    var addUserTemplate = kendo.template($("#addUserTemplate").html());
    var wndAddUser = $("#wndAddUser")
        .kendoWindow({
            title: "Thêm Người chơi",
            modal: true,
            visible: false,
            resizable: false,
            width: 500,
            activate: onActivateAddUser

        }).data("kendoWindow");

    $("#wndAddUser").keypress(function(event){
        //if the key press is ESC
        if (event.keyCode === 27) {
            //close the KendoUI window
            $("#wndAddUser").data("kendoWindow").close();
        }

        if(event.keyCode === 13) {
            saveUser();
        }
    });


    function onActivateAddUser(){
        $("#txtUserName").focus();
        $('#btnSaveUser').click(function(){
            saveUser();
        });

        $('#btnClose').click(function(){
            closeUserWindow();
        });
    }

    function addUser(){
        wndAddUser.content(addUserTemplate({
            "id":'',"username":'',"level_range":''}));
        wndAddUser.center().open();
        wnd = $("#wndAddUser").data("kendoWindow");
        wnd.title("Thêm người chơi:");
    }

    function saveUser(){
        if(!validateUserSave())
            return;
        id = $("#txtUserId").val();
        username = $("#txtUserName").val();
        level_range = $('#cboLevelRange').val();

        if(!id){
            $.post('<?php echo '/admin/league/add-user'  ?>',
                {username:username,level_range:level_range, league_id:<?php echo $item->id?>}
                ,function(result){
                    if(result.success){
                        closeUserWindow();
                        $("#spnStatus").html('');
                        $('#tblUser tr:last').after(result.data);
                    }else{
                        $('#spnStatus').html(result.msg);
                    }

                },'json');
        }else{
            $.post('<?php echo '/admin/league/edit-user'  ?>',
                {id:id,username:username,level_range:level_range}
                ,function(result){
                    if(result.success){
                        closeUserWindow();
                        $("#spnStatus").html('');
                        $('#leagueUserRow' + id +' td:eq(1)').html(result.data.username);
                        $('#leagueUserRow' + id +' td:eq(2)').html(result.data.level_range);


                    }else{
                        $('#spnStatus').html(result.msg);
                    }

                },'json');
        }

    }

    function validateUserSave(){
        if(!$("#txtUserName").val()){
            $("#spnStatus").html('Vui lòng nhập Tên.');
            $("#txtUserName").focus();
            return false;
        }

        return true;
    }

    function closeUserWindow(){
        wndAddUser.close();
    }

    function deleteUser(id){
        bootbox.confirm("Bạn chắc chắn muỗn xóa?", function(data) {
            if(data){

                $.post('/admin/league/delete-user',
                    {id:id}
                    ,function(result){
                        if(result.success){
                            $('#leagueUserRow'+id).remove();
                        }else{
                            $('#ajaxMsg').html(result.msg);
                        }

                    },'json');
            }
        });
    }

    function editUser(id){
        username = $('#leagueUserRow' + id +' td:eq(1)').html();
        level_range = $('#leagueUserRow' + id +' td:eq(2)').html();

        wndAddUser.content(addUserTemplate({
            "id":id,"username":username,"level_range":level_range}));
        wnd = $("#wndAddUser").data("kendoWindow");
        wnd.title("Sửa người chơi: " + username);
        wndAddUser.center().open();
        $('#cboLevelRange').val(level_range);
    }


    //----for match window


    var addMatchTemplate = kendo.template($("#addMatchTemplate").html());
    var wndAddMatch = $("#wndAddMatch")
        .kendoWindow({
            title: "Thêm vòng đấu",
            modal: true,
            visible: false,
            resizable: false,
            width: 500,
            activate: onActivateAddMatch

        }).data("kendoWindow");

    $("#addMatchTemplate").keypress(function(event){
        //if the key press is ESC
        if (event.keyCode === 27) {
            //close the KendoUI window
            $("#wndAddMatch").data("kendoWindow").close();
        }

        if(event.keyCode === 13) {
            saveMatch();
        }
    });


    function onActivateAddMatch(){

        $('#btnSaveMatch').click(function(){
            saveMatch();
        });

        $('#btnCloseMatch').click(function(){
            closeMatchWindow();
        });

        $('#cboMatchRound').change(function(){

            loadPlayUser();
        })

        $('#cboMatchLevelRange').change(function(){

            loadPlayUser();
        })
    }

    function addMatch(){

        wndAddMatch.content(addMatchTemplate({
            "id":'',"video":''}));
        wndAddMatch.center().open();
        wnd = $("#wndAddMatch").data("kendoWindow");
        wnd.title("Thêm trận đấu: ");
        loadPlayUser();
    }

     function loadPlayUser(){
        levelRange = $('#cboMatchLevelRange').val();
        round = $('#cboMatchRound').val();
         $('#cboFirstUser').empty();
         $('#cboSecondUser').empty();

        $.post('<?php echo '/admin/league/load-round-player'  ?>',
            {levelRange:levelRange,round:round, league_id:<?php echo $item->id?>}
            ,function(result){
                if(result.success){
                    $("#spnMatchStatus").html('');
                    addUserToSelect('cboFirstUser',result.data );
                    addUserToSelect('cboSecondUser',result.data )
                }else{
                    $('#spnMatchStatus').html(result.msg);
                }

            },'json');
    }

     function addUserToSelect(selectId, data){
        for (var obj in data) {
            $('#'+selectId).append('<option value="'+data[obj].id+'">'+data[obj].username+'</option>');
        }

    }
    function saveMatch(){
        if(!validateMatchSave())
            return;
        id = $("#txtMatchId").val();
        levelRange = $("#cboMatchLevelRange").val();
        round = $('#cboMatchRound').val();
        firstUser = $('#cboFirstUser').val();
        secondUser = $('#cboSecondUser').val();
        result  = $('#cboResult').val();
        video  = $('#txtVideo').val();


        if(!id){
            $.post('<?php echo '/admin/league/add-match'  ?>',
                {levelRange:levelRange,round:round,firstUser:firstUser,secondUser:secondUser,
                    result:result, video:video, league_id:<?php echo $item->id?>}
                ,function(result){
                    if(result.success){
                        closeMatchWindow();
                        $("#spnMatchStatus").html('');
                        $('#tblMatch tr:last').after(result.data);
                    }else{
                        $('#spnMatchStatus').html(result.msg);
                    }

                },'json');
        }else{
            $.post('<?php echo '/admin/league/edit-user'  ?>',
                {id:id,username:username,level_range:level_range}
                ,function(result){
                    if(result.success){
                        closeMatchWindow();
                        $("#spnMatchStatus").html('');
                        $('#leagueUserRow' + id +' td:eq(1)').html(result.data.username);
                        $('#leagueUserRow' + id +' td:eq(2)').html(result.data.level_range);


                    }else{
                        $('#spnMatchStatus').html(result.msg);
                    }

                },'json');
        }

    }

    function validateMatchSave(){
        if($("#cboFirstUser").val() == $("#cboSecondUser").val()){
            $("#spnMatchStatus").html('2 Người chơi phải khác nhau');

            return false;
        }
        return true;
    }

    function closeMatchWindow(){
        wndAddMatch.close();
    }


    function deleteMatch(id){
        bootbox.confirm("Bạn chắc chắn muỗn xóa?", function(data) {
            if(data){

                $.post('/admin/league/delete-match',
                    {id:id}
                    ,function(result){
                        if(result.success){
                            data = result.data;
                            for (var obj in data) {
                                $('#matchRow'+data[obj]).remove();
                            }

                        }else{
                            $('#ajaxMsg').html(result.msg);
                        }

                    },'json');
            }
        });
    }



</script>
