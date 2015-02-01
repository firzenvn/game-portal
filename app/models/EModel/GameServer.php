<?php namespace EModel;


use Util\Model\EloquentBaseModel;

class GameServer extends EloquentBaseModel
{


    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'game_servers';

    public $timestamps = false;

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array('name', 'ip', 'url', 'secret_key', 'active', 'game_id', 'order_number', 'sid', 'apply_for');

    protected $validationRules = [
        'name'    => 'required|min:2',
        'ip'    => 'required|min:5',
        'url'    => 'required|min:5',
        'secret_key'    => 'required|min:5',
        'game_id'=> 'required'
    ];

    public function getFullNameAttribute(){
        return $this->attributes['sid'].': '.$this->attributes['name'];
    }



}
