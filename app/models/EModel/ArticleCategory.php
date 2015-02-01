<?php namespace EModel;


use Util\Model\EloquentBaseModel;
use Util\Model\Traits\BlamableObserver;

class ArticleCategory extends EloquentBaseModel
{


    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'article_category';

    public $timestamps = false;

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array('article_id', 'category_id', 'is_game_main');

    protected $validationRules = [
        'article_id'    => 'required',
        'category_id'    => 'required',
    ];



}
