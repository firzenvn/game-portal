<?php namespace EModel;


use Illuminate\Support\Facades\View;
use Pingpong\Widget\Facades\Widget;
use Util\Model\EloquentBaseModel;
use Util\Model\Traits\BlamableObserver;
use Util\Model\Traits\UploadableRelationship;

class GameVideo extends EloquentBaseModel
{

    protected $table    = 'game_videos';

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array('game_id', 'youtobe_code');

    protected $validationRules = [
        'game_id'    => 'required',
    ];

    public static function boot()
    {
        parent::boot();

    }



}
