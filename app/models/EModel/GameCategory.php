<?php namespace EModel;


use Util\Model\EloquentBaseModel;

class GameCategory extends EloquentBaseModel
{


    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'game_categories';

//    public $timestamps = false;

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array('game_id', 'category_id');

    protected $validationRules = [
        'game_id'    => 'required',
        'category_id'    => 'required',
    ];



}
