<?php namespace EModel;


use Util\Model\EloquentBaseModel;

class Tags extends EloquentBaseModel
{

    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'tags';

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array('tag','taggable_id','taggable_type', 'alias');

    protected $validationRules = [];

}
