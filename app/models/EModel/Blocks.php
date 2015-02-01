<?php namespace EModel;


use Illuminate\Support\Facades\View;
use Pingpong\Widget\Facades\Widget;
use Util\Model\EloquentBaseModel;
use Util\Model\Traits\BlamableObserver;

class Blocks extends EloquentBaseModel
{



    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'blocks';




    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array('params', 'active', 'name', 'wrap_class', 'wrap_style', 'html', 'view_file', 'type');

    protected $validationRules = [
        'name'    => 'required|min:2|unique:blocks,name,<id>',
    ];

    public static function boot()
    {
        parent::boot();

    }


    public function isHtml(){
        if($this->html)
            return true;
    }

    public function registerMyself(){
        if(!$this->active)
            return ;
        if($this->isHtml() )
            Widget::register($this->name, function(){
                return $this->html;
            });
        else{

            Widget::register($this->name, function(){
                $myParams = json_decode($this->params);
                return View::make('includes.blocks.'.$this->view_file, (array)$myParams);

            });
        }
    }
}
