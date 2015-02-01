<?php

namespace Controllers\Admin;

class DashController extends AdminBaseController {



    public function index()
    {
         $this->layout->content = View::make('admin.index');
    }



}
