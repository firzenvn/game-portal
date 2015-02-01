<?php


use Services\PageService;
use Util\Exceptions\SystemException;



class FrontBaseController extends BaseController {
    protected $page;
    function __construct(PageService $pageService )
    {
        $this->pageService = $pageService;
    }

    public function initRoutablePage(){
        $pageId = Input::get('pageId');
        $tmpPage = $this->pageService->requireById($pageId);
        if(!$tmpPage) throw new SystemException('Trang yêu cầu không tồn tại');
        $this->page = $tmpPage;
        $myTemplate = $this->page->template;
        if($myTemplate){
            $this->layout = 'layout.'.$myTemplate->file_name;
            $this->setupLayout();
            $this->layout->with('pageItem', $this->page);
        }
        $this->registerMyWidget();
    }

    public function registerMyWidget(){
        $myBlocks = $this->page->blocks();
        foreach ($myBlocks as $aBlock) {
            $aBlock->registerMyself();
        }

    }

}