<?php
$edit_url = '/'.$urlSegment.'/catalogs/country-edit/';
$new_url = '/'.$urlSegment.'/catalogs/country-new/';
$delete_url= '/'.$urlSegment.'/catalogs/delete-country/';
?>


<h3>Quản lý danh mục</h3>
{{-- The error / success messaging partial --}}
@include('includes.messaging')

<div class="action-ctn">
    <a  id="btnAdd" class="btn btn-primary btn-sm">Thêm mới</a>
    <a  id="btnEdit" class="btn btn-primary btn-sm">Sửa</a>
    <a  id="btnDown" class="btn btn-primary btn-sm"><span style="font-size: 1.5em" class="glyphicon glyphicon-arrow-down"></span></a>
    <a  id="btnUp" class="btn btn-primary btn-sm"><span style="font-size: 1.5em" class="glyphicon glyphicon-arrow-up"></span></a>
    <a  id="btnDelete" class="btn btn-primary btn-sm">Xóa</a>
</div>
<div class="content-ctn">
    <div class="tree-ctn">

    </div>

</div>

<div id="wndAddCategory"></div>
<script type="text/x-kendo-template" id="addCategoryTemplate">
    <div id="add-category-container">
        <dl>
            <dt style="padding: 5px">
            <div> Nhóm cha : #= parent_name #
                <input  id='txtParentId'  name="txtParentId" type="hidden"    value='#= parent_id #'>
            </div>
            </dt>
            <dt style="padding: 5px"><div style="width: 70px; float:left"> Mã(*):</div>
            <input  id='txtCategoryId'  name="txtCategoryId" type="hidden"    value='#= id #'>
            <input  id='txtCategoryCode'  name="txtCategoryCode" class="k-textbox"  required  value='#= code #'>

            </dt>
            <dt style="padding: 5px"><div style="width: 70px;float:left"> Tên(*):</div>
            <input  style="width:400px" class="k-textbox"  required  id='txtCategoryName' value='#= name #'>


            </dt>
            <dt style="padding: 5px"><div style="width: 70px; float:left"> Mô tả:</div>
            <input style="width:400px"  class="k-textbox"  required  id='txtCategoryDescription' value='#= description #'>

            </dt>
            <dt style="padding: 5px">
                <span style="color:red" id="spnStatus"></span>
            </dt>
        </dl>
        <div style="padding: 10px 0px" >
            <a id="btnSaveMyCategory" class="k-button k-button-icontext k-grid-update" >
                <span class="k-icon k-update"></span>
                Lưu
            </a>
            <a id="btnCloseMyCategory" class="k-button k-button-icontext k-grid-cancel">
                <span class="k-icon k-cancel"></span>
                Cancel
            </a>
        </div>
    </div>
</script>



<script type="text/javascript">

    var categoryAsKendoDataSourceJson = {{$kendoDataSource}};
    if(treeview)
        treeview.destroy();
    var treeview = $(".tree-ctn").kendoTreeView({
        dataSource: categoryAsKendoDataSourceJson,
        change: onSelectCategory
    }).data("kendoTreeView");


    treeview.expand(".k-item");
    treeview.select(".k-first");
    function onSelectCategory(e){
        selectedNode = getTreeSelectedNode(treeview)
        data = treeview.dataItem(selectedNode);

        if(data.modifiable == 0){
            $(".action-ctn").find('a').each(function(){
                $(this).hide()
            })
        }else{
            $(".action-ctn").find('a').each(function(){
                $(this).show()
            })
        }
        $('#btnAdd').show();
        $.post('<?php echo '/admin/catalogs/get-category-parent'  ?>',
            {id:data.id}
            ,function(result){
                if (result.success){
                    if(result.upable)
                        $('#btnUp').show();
                    else
                        $('#btnUp').hide();
                    if(result.downable)
                        $('#btnDown').show();
                    else
                        $('#btnDown').hide();
                }else{
//                    $('#error_ctn').removeClass('blue_text').html(result.msg);
                    alert(result.msg);
                }
            },'json');


    }

    function getTreeSelectedNode(treeview){
        selectedNode = treeview.select();
        if (selectedNode.length == 0) {
            treeview.select(".k-item:first");
            selectedNode = treeview.select();
        }
        return selectedNode;
    }

    $('#btnAdd').click(function(){
        selectedNode = getTreeSelectedNode(treeview)
        data = treeview.dataItem(selectedNode);
        doAdd(data)

    });

    $('#btnEdit').click(function(){
        selectedNode = getTreeSelectedNode(treeview)
        data = treeview.dataItem(selectedNode);
        editCategory(data)

    });

    $('#btnUp').click(function(){
        selectedNode = getTreeSelectedNode(treeview)
        data = treeview.dataItem(selectedNode);
        upCategory(data,treeview)

    });

    $('#btnDown').click(function(){
        selectedNode = getTreeSelectedNode(treeview)
        data = treeview.dataItem(selectedNode);
        downCategory(data,treeview)

    });

    function editCategory(data){
        wndAddCategory.content(addCategoryTemplate({"id":data.id, "parent_id":'',"parent_name":'',
            "code":data.code,"name":data.text,"description":data.description}));
        wndAddCategory.title("Sửa nhóm "+ data.text)
        wndAddCategory.center().open();
    }

    $('#btnDelete').click(function(){
        selectedNode = getTreeSelectedNode(treeview)
        data = treeview.dataItem(selectedNode);
        bootbox.confirm("Bạn chắc chắn muỗn xóa "+ data.name, function(result) {
            if(result){
                deleteCategory(data,treeview)
            }
        });

    });

    function deleteCategory(data,treeview){
        $.post('<?php echo '/admin/catalogs/remove-category'  ?>',
            {id:data.id}
            ,function(result){
                if (result.success){
                    selectedNode = getTreeSelectedNode(treeview)
                    treeview.remove(selectedNode);
                    $('#btnUp').hide();
                    $('#btnDown').hide();
//                    $('#error_ctn').addClass('blue_text').html('Xóa nhóm: <b>'+ data.name + '</b> thành công');

                }else{
//                    $('#error_ctn').removeClass('blue_text').html(result.msg);

                }
            },'json');

    }

    function doAdd(data){
        wndAddCategory.content(addCategoryTemplate({"parent_id":data.id,"parent_name":data.text,
            "id":'',"code":'',"name":'',"description":""}));
        wndAddCategory.center().open();
    }

    function upCategory(data,treeview){
        $.blockUI({ message: 'Vui lòng chờ' });
        $.post('<?php echo '/admin/catalogs/up-category'  ?>',
            {id:data.id}
            ,function(result){
                $.unblockUI();
                if (result.success){
                    window.location.assign("/admin/catalogs/categories/"+data.group_code);
                }else{
//                    $('#error_ctn').removeClass('blue_text').html(result.msg);
//                    alert(result.msg);
                }
            },'json');

    }

    function downCategory(data,treeview){
        $.blockUI({ message: 'Vui lòng chờ' });
        $.post('<?php echo '/admin/catalogs/down-category'  ?>',
            {id:data.id}
            ,function(result){
                $.unblockUI();
                if (result.success){
                    window.location.assign("/admin/catalogs/categories/"+data.group_code);
                }else{
                }
            },'json');

    }

    var addCategoryTemplate = kendo.template($("#addCategoryTemplate").html());
    var wndAddCategory = $("#wndAddCategory")
        .kendoWindow({
            title: "Thêm nhóm",
            modal: true,
            visible: false,
            resizable: false,
            width: 500,
            activate: onActivateAddCategory

        }).data("kendoWindow");

    $("#wndAddCategory").keypress(function(event){
        //if the key press is ESC
        if (event.keyCode === 27) {
            //close the KendoUI window
            $("#wndAddCategory").data("kendoWindow").close();
        }

        if(event.keyCode === 13) {
            saveCategory(treeview);
        }
    });

    function onActivateAddCategory(){
        $("#txtCategoryCode").focus();
        $('#btnSaveMyCategory').click(function(){
            saveCategory(treeview);
        });

        $('#btnCloseMyCategory').click(function(){
            closeCategoryWindow();
        });
    }


    function saveCategory(treeview){
        if(!validateCategorySave())
            return;
        id = $("#txtCategoryId").val();
        name = $("#txtCategoryName").val();
        code = $("#txtCategoryCode").val();
        description = $("#txtCategoryDescription").val();
        parentId = $("#txtParentId").val();


        $.post('<?php echo '/admin/catalogs/save-category'  ?>',
            {name:name,code:code,description:description, id:id,parentId:parentId}
            ,function(result){
                if (result.success){
                    closeCategoryWindow();
                    $("#spnStatus").html('');
                    selectedNode = getTreeSelectedNode(treeview);
                    categoryId = result.id;
                    if(!id)
                        treeview.append({id:categoryId,
                            name : name,
                            code : code,
                            description : description,
                            text: name
                        }, selectedNode);
                    else{

                        dataItem = treeview.dataItem(selectedNode);
                        dataItem.code = code;
                        dataItem.description = description;
                        dataItem.name = name;
                        dataItem.text = name;
                        dataItem.trigger("change", { action: "itemchange", field: "text" });
                    }
//                    $('#error_ctn').addClass('blue_text').html('Cập nhật thành công');
                }else{
                    $("#spnStatus").html(result.msg);
                }
            },'json');
    }

    function validateCategorySave(){
        if(!$("#txtCategoryCode").val()){
            $("#spnStatus").html('Vui lòng nhập mã.');
            $("#txtCategoryCode").focus();
            return false;
        }

        if(!$("#txtCategoryName").val()){
            $("#spnStatus").html('Vui lòng nhập tên.');
            $("#txtCategoryName").focus();
            return false;
        }


        return true;
    }

    function closeCategoryWindow(){
        wndAddCategory.close();
    }

</script>
