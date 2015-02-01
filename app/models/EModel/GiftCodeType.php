<?php namespace EModel;


use Util\Model\EloquentBaseModel;

class GiftCodeType extends EloquentBaseModel
{

    protected $fillable = [];

    protected $table = 'giftcode_type';

    public function game()
    {
        return $this->belongsTo('EModel\Games','game_id');
    }
}