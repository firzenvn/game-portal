<?php

namespace Controllers\Admin;

use EModel\Pages;
use EModel\Templates;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Services\PageService;
use Util\AppDirectoryHelper;
use Util\Exceptions\EntityNotFoundException;


class PagesController extends AdminBaseController {

    function __construct(PageService $pageService)
    {
        parent::__construct();
        $this->pageService = $pageService;

    }


    public function getIndex()
    {
        $items = $this->pageService->getAllPageWithTemplate();
        $this->layout->content = View::make('admin.page_index')
            ->with( 'items' ,  $items);
    }


    public function getNew(){
//        $allTemplateFile = AppDirectoryHelper::getAllTemplateFile();
        $allTemplates = Templates::where('active', '=', 1)->get();
        $allControllers = AppDirectoryHelper::getAllFrontController();
        $this->layout->content = View::make('admin.page_new')
            ->with('allTemplates', $allTemplates)
            ->with('allControllers', $allControllers);
    }


    public function postNew(){

        $paramArr = Input::all();
        $newRecord = new Pages( array('name'=>$paramArr['txtName'], 'template_id'=>$paramArr['cboTemplate'],
            'description'=>$paramArr['txtDescription'], 'title'=>$paramArr['txtTitle'],
            'route'=>$paramArr['txtRoute'], 'controller'=>$paramArr['cboController'],
            'action'=>$paramArr['txtAction']));

        $valid = $newRecord->isValid();
        if( !$valid )
            return Redirect::to( '/admin/pages/new')->
                with( 'errors' , $newRecord->getErrors() )->withInput();
        $newRecord->save();
        return Redirect::to( '/admin/pages/new' )->
            with( 'success' , new MessageBag( array( 'Thêm mới thành công' ) ) );
    }

    public function getDelete( $id ){

        $page = $this->pageService->requireById($id);
        $page->delete();
        $message = 'Xóa thành công.';
        return Redirect::to( '/admin/pages' )
            ->with('success', new MessageBag( array( $message ) ) );

    }

    public function getEdit( $id ){

        try{
            $item = $this->pageService->requireById($id);
        } catch( EntityNotFoundException $e ){
            return Redirect::to( '/admin/pages')->
                with('errors', new MessageBag( array("Không tìm thấy page ID:".$id .".") ) );
        }

        $allTemplates = Templates::where('active', '=', 1)->get();
        $allControllers = AppDirectoryHelper::getAllFrontController();

        $this->layout->content = View::make('admin.page_edit')
            ->with( 'item' , $item )
            ->with('allTemplates', $allTemplates)
            ->with('allControllers', $allControllers);;

    }

    public function postEdit($id){

        $record = $this->pageService->requireById( $id );
        $paramArr = Input::all();
        $record->fill(    array('name'=>$paramArr['txtName'], 'template_id'=>$paramArr['cboTemplate'],
            'description'=>$paramArr['txtDescription'], 'title'=>$paramArr['txtTitle'],
            'route'=>$paramArr['txtRoute'], 'controller'=>$paramArr['cboController'],
            'action'=>$paramArr['txtAction']) );

        $valid =  $record->isValid();

        if( !$valid )
            return Redirect::to( '/admin/pages/edit/'.$id)->
                with( 'errors' , $record->getErrors() )->withInput();

        // Run the hydration method that populates anything else that is required / runs any other
        // model interactions and save it.
        $record->save();

        // Redirect that shit man! You did good! Validated and saved, man mum would be proud!
        return Redirect::to( '/admin/pages' )->
            with( 'success' , new MessageBag( array( 'Sửa thành công' ) ) );
    }

    function getEditBlock($pageId){
        $this->layout = 'layout.layout_edit_block';
        $this->setupLayout();
        $pageItem = Pages::findOrFail($pageId);
        $this->layout->content = View::make('admin.page_edit_block')->with('pageItem', $pageItem);
    }

    function postLoadPageContent($pageId){
        $pageItem = Pages::find($pageId);
        return View::make("front.home")->with("pageItem",$pageItem);

    }
}
