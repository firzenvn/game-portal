<?php namespace EModel;


use Util\Model\EloquentBaseModel;
use Util\Model\Traits\BlamableObserver;

class Templates extends EloquentBaseModel
{




    protected $table    = 'templates';

    protected $fillable = array('name', 'file_name', 'description');

    protected $validationRules = [
        'name'    => 'required|min:2|unique:template,name,<id>',
//        'file_name'    => 'required|min:2|unique:template,file_name,<id>',
    ];

    public static function boot()
    {
        parent::boot();

    }

    public function pages()
    {
        return $this->hasMany('EModel\Page\Pages', 'template_id');
    }


}
