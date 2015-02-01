<?php namespace EModel;


use Util\Model\EloquentBaseModel;
use Util\Model\Traits\BlamableObserver;

class Pages extends EloquentBaseModel
{


    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'pages';



    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array('controller', 'action', 'name', 'route', 'template_id', 'title', 'description');

    protected $validationRules = [
        'name'    => 'required|min:2|unique:pages,name,<id>',
        'controller'    => 'required|min:2',
        'action'    => 'required|min:2',
        'route'    => 'required|min:1|unique:pages,route,<id>',
    ];

    public static function boot()
    {
        parent::boot();

    }

    public function template()
    {
        return $this->belongsTo('EModel\Templates','template_id');
    }

    public function blocks()
    {
        $result = Blocks::join('page_block', 'blocks.id', '=', 'page_block.block_id')
        ->where('page_block.page_id', '=', $this->id)
            ->where('blocks.active', '=', 1)
        ->get();
        return $result;
    }


}
