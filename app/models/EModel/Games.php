<?php namespace EModel;


use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Request;

use Log;
use Util\Model\EloquentBaseModel;

use Util\Model\Traits\TaggableRelationship;
use Util\Model\Traits\UploadableRelationship;

class Games extends EloquentBaseModel
{

    use TaggableRelationship; // Enable The Tags Relationships
    use UploadableRelationship; // Enable The Uploads Relationships

    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'games';

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array('name',  'description', 'active', 'subdomain', 'tpl', 'exchange_rate','unit');

    protected $validationRules = [
        'name'     => 'required',
        'subdomain'     => 'required'
    ];


    public static function boot()
    {
        parent::boot();

    }

    public function categories()
    {
        return $this->belongsToMany('EModel\Category', 'game_categories', 'game_id');
    }

    public function getPrimaryCategory(){
        $allMyCategories =  $this->categories;
        $gameCode = Config::get('danhmuc.category-group-code.game');

        if($allMyCategories){
            foreach ($allMyCategories as $aCategory) {
                  if($gameCode == $aCategory->group_code)
                      return $aCategory;
            }
        }
    }

    public function getSubCategories(){
        $allMyCategories =  $this->categories;
        $gameCode = Config::get('danhmuc.category-group-code.sub-game');
        $resultArr = array();
        if($allMyCategories){
            foreach ($allMyCategories as $aCategory) {
                if($gameCode == $aCategory->group_code)
                    array_push($resultArr, $aCategory);
            }
        }
        return $resultArr;
    }

    public function servers(){
        return $this->hasMany('EModel\GameServer', 'game_id');
    }

    public function getPrivateLink(){
        return 'http://'.$this->subdomain.'.'.parse_url(Request::url(), PHP_URL_HOST);
    }
}