<?php

namespace Controllers\Admin;

use EModel\Blocks;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Services\BlockService;
use Util\AppDirectoryHelper;
use Util\CommonHelper;
use Util\Exceptions\SystemException;

class BlocksController extends AdminBaseController {

    function __construct(BlockService $blockService)
    {
        parent::__construct();
        $this->blockService = $blockService;

        /*$this->beforeFilter('permission', array('except'=>array(
            'getIndex'
        )));*/
    }

    public function getIndex()
    {
        $items = Blocks::orderBy('created_at')->get();
        $this->layout->content = View::make('admin.block_index')
            ->with( 'items' ,  $items);
    }

    public function getNew()
    {
        $allBlockType = Config::get('danhmuc.block-type');
        $allBlockFile = AppDirectoryHelper::getAllBlockFile();

        $this->layout->content = View::make('admin.block_new')
            ->with('allBlockFile', $allBlockFile)
            ->with('allBlockType', $allBlockType);

    }

    public function postNew(){
        $paramArr = Input::all();

        $group = $paramArr['group'];
        if($group == 'html')
            $dataArr = array('name'=>$paramArr['name'], 'html'=>$paramArr['content'],
                'active'=>$paramArr['active'], 'type'=>$paramArr['type'],
                'wrap_class'=>$paramArr['wrapClass'],'wrap_style'=>$paramArr['wrapStyle']);
        else{
//            $paramArr['params'] = $paramArr['params']?$paramArr['params']:array();
            $params = CommonHelper::keyValArrToJsonString(Input::get('params'));
            $dataArr = array('name'=>$paramArr['name'], 'view_file'=>$paramArr['viewFile'],
                'active'=>$paramArr['active'], 'type'=>$paramArr['type'], 'params'=>$params,
                'wrap_class'=>$paramArr['wrapClass'],'wrap_style'=>$paramArr['wrapStyle']);
        }
        $newRecord = new Blocks( $dataArr);

        $valid = $newRecord->isValid();
        if( !$valid )
            return Response::json(array('success'=>false, 'msg'=>$this->errorToString( $newRecord->getErrors())));

        $newRecord->save();

        return Response::json(array('success'=>true, 'msg'=> $this->successToString('Thêm block thành công!')));
    }

    public function getDelete( $id ){
        Blocks::where('id', '=', $id)->delete();

        $message = 'Xóa thành công.';
        return Redirect::to( '/admin/blocks' )
            ->with('success', new MessageBag( array( $message ) ) );

    }

    public function getEdit($id)
    {
        $item = Blocks::find($id);
        $allBlockType = Config::get('danhmuc.block-type');
        $allBlockFile = AppDirectoryHelper::getAllBlockFile();

        $this->layout->content = View::make('admin.block_edit', array(
            'item'=>$item,
            'allBlockFile'=>$allBlockFile,
            'allBlockType'=>$allBlockType
        ));
    }

    public function postEdit($id)
    {
        $record = Blocks::find($id);

        $paramArr = Input::all();

        $group = $paramArr['group'];
        if($group == 'html')
            $dataArr = array('name'=>$paramArr['name'], 'html'=>$paramArr['content'],
                'active'=>$paramArr['active'], 'type'=>$paramArr['type'],
                'wrap_class'=>$paramArr['wrapClass'],'wrap_style'=>$paramArr['wrapStyle']);
        else{
            $params = CommonHelper::keyValArrToJsonString(Input::get('params'));
            $dataArr = array('name'=>$paramArr['name'], 'view_file'=>$paramArr['viewFile'],
                'active'=>$paramArr['active'], 'type'=>$paramArr['type'], 'params'=>$params,
                'wrap_class'=>$paramArr['wrapClass'],'wrap_style'=>$paramArr['wrapStyle']);
        }

        $valid = $record->isValid($dataArr);
        if( !$valid )
            return Response::json(array('success'=>false, 'msg'=>$this->errorToString( $record->getErrors())));

        $record->update($dataArr);

        return Response::json(array('success'=>true, 'msg'=> $this->successToString('Sửa block thành công!')));
    }
}
