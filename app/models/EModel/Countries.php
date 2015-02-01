<?php namespace EModel;


use Util\Model\EloquentBaseModel;
use Util\Model\Traits\BlamableObserver;

class Countries extends EloquentBaseModel
{

    use \Util\Model\Traits\CreatedBy;
    use \Util\Model\Traits\UpdatedBy;
    use \Util\Model\Traits\DeletedBy;

    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'country';

    public $timestamps = false;

    protected $blamable = array('created', 'updated', 'deleted');

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array('code', 'name', 'created_by');

    protected $validationRules = [
        'code'    => 'required|min:2|max:5|alpha_dash|unique:country,code,<id>',
        'name'    => 'required|min:2|unique:country,name,<id>',
    ];

    public static function boot()
    {
        parent::boot();
        Countries::observe(new BlamableObserver());
    }


}
