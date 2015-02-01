<?php namespace EModel;


use Util\Model\EloquentBaseModel;
use Util\Model\Traits\BlamableObserver;

class GameArticle extends EloquentBaseModel
{


    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'game_articles';

    public $timestamps = false;

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array('game_id', 'article_id');

    protected $validationRules = [
        'game_id'    => 'required',
        'article_id'    => 'required',
    ];

}
