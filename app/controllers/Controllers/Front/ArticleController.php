<?php

namespace Controllers\Front;

class ArticleController extends FrontBaseController {


    public function showIndex()
    {
        return View::make('article/index');
    }
    public function showSingle($articleId)
    {
        return View::make('single');
    }
}
