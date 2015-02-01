<?php namespace EModel;


use Illuminate\Support\Facades\View;
use Pingpong\Widget\Facades\Widget;
use Util\Model\EloquentBaseModel;
use Util\Model\Traits\BlamableObserver;
use Util\Model\Traits\UploadableRelationship;

class GameGallery extends EloquentBaseModel
{

    use UploadableRelationship; // Enable The Uploads Relationships

    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'game_galleries';




    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array('game_id', 'description', 'url', 'category_id');

    protected $validationRules = [
        'game_id'    => 'required',
    ];

    public static function boot()
    {
        parent::boot();

    }



}
