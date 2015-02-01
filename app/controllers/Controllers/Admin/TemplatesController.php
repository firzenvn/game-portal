<?php

namespace Controllers\Admin;

use EModel\Templates;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Services\TemplateService;
use Util\AppDirectoryHelper;
use Util\Exceptions\EntityNotFoundException;


class TemplatesController extends AdminBaseController {

    function __construct(TemplateService $templateService)
    {
        parent::__construct();
        $this->templateService = $templateService;

    }


    public function getIndex()
    {
        $this->layout->content = View::make('admin.template_index')
            ->with( 'items' , Templates::all());
    }


    public function getNew(){
        $allTemplateFile = AppDirectoryHelper::getAllTemplateFile();

        $this->layout->content = View::make('admin.template_new')->
            with('templateFiles', $allTemplateFile);
    }


    public function postNew(){
        $newRecord = new Templates(Input::all());
        $valid = $newRecord->isValid();
        if( !$valid )
            return Redirect::to( '/admin/templates/new')->
                with( 'errors' , $newRecord->getErrors() )->withInput();
        $newRecord->save();
        return Redirect::to( '/admin/templates/new' )->
            with( 'success' , new MessageBag( array( 'Thêm mới thành công' ) ) );
    }

    public function getDelete( $id ){

        $template = $this->templateService->requireById($id);
        $template->delete();
        $message = 'Xóa thành công.';
        return Redirect::to( '/admin/templates' )
            ->with('success', new MessageBag( array( $message ) ) );

    }

    public function getEdit( $id ){

        try{
            $item = $this->templateService->requireById($id);
        } catch( EntityNotFoundException $e ){
            return Redirect::to( '/admin/templates')->
                with('errors', new MessageBag( array("Không tìm thấy tempalte ID:".$id .".") ) );
        }

        $allTemplateFile = AppDirectoryHelper::getAllTemplateFile();
        $this->layout->content = View::make('admin.template_edit')
            ->with( 'item' , $item )
            ->with('templateFiles', $allTemplateFile);

    }

    public function postEdit($id){

        $record = $this->templateService->requireById( $id );
        $record->fill( Input::all() );

        $valid =  $record->isValid();

        if( !$valid )
            return Redirect::to( '/admin/templates/edit/'.$id)->
                with( 'errors' , $record->getErrors() )->withInput();

        // Run the hydration method that populates anything else that is required / runs any other
        // model interactions and save it.
        $record->save();

        // Redirect that shit man! You did good! Validated and saved, man mum would be proud!
        return Redirect::to( '/admin/templates' )->
            with( 'success' , new MessageBag( array( 'Sửa thành công' ) ) );
    }
}
