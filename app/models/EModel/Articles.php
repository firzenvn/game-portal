<?php namespace EModel;


use App;
use Illuminate\Support\Facades\Config;
use Str, Input;
use Util\CommonConstant;
use Util\CommonHelper;
use Util\Exceptions\BusinessException;
use Util\Model\EloquentBaseModel;
use Util\Model\Traits\BlamableObserver;
use Util\Model\Traits\TaggableRelationship;
use Util\Model\Traits\UploadableRelationship;

class Articles extends EloquentBaseModel
{

    use TaggableRelationship; // Enable The Tags Relationships
    use UploadableRelationship; // Enable The Uploads Relationships



    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'articles';

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array('title',  'content', 'active', 'description', 'keyword');

    protected $validationRules = [
        'title'     => 'required',
        'slug'      => 'required|unique:articles,slug,<id>',
        'content'   => 'required'
    ];

    /**
     * Fill the model up like we usually do but also allow us to fill some custom stuff
     * @param  array $array The array of data, this is usually Input::all();
     * @return void
     */
    public function fill( array $attributes )
    {

        parent::fill( $attributes );

        $this->slug = CommonHelper::vietnameseToASCII($this->title);

    }

    public static function boot()
    {
        parent::boot();

    }

    public function categories()
    {
        return $this->belongsToMany('EModel\Category', 'article_category', 'article_id');
    }

    public function getPrimaryCategory(){
        $allMyCategories =  $this->categories;
        $articleCode = CommonConstant::CATEGORY_ARTICLE;

        if($allMyCategories){
            foreach ($allMyCategories as $aCategory) {
                  if($articleCode == $aCategory->group_code)
                      return $aCategory;
            }
        }
    }

    public function getSubCategories(){
        $allMyCategories =  $this->categories;
        $articleCode =  CommonConstant::CATEGORY_SUB_ARTICLE;
        $resultArr = array();
        if($allMyCategories){
            foreach ($allMyCategories as $aCategory) {
                if($articleCode == $aCategory->group_code)
                    array_push($resultArr, $aCategory);
            }
        }
        return $resultArr;
    }

    public function getPosCategories(){
        $allMyCategories =  $this->categories;
        $articleCode = CommonConstant::CATEGORY_POSITION_ARTICLE;
        $resultArr = array();
        if($allMyCategories){
            foreach ($allMyCategories as $aCategory) {
                if($articleCode == $aCategory->group_code)
                    array_push($resultArr, $aCategory);
            }
        }
        return $resultArr;
    }

    public function games()
    {
        return $this->belongsToMany('EModel\Games','game_articles','article_id','game_id');
    }

    public function getGameUrl($categorySlug){
        return '/tin-tuc/'.$categorySlug.'/'.$this->slug.'-'.$this->id;
    }

    public function getGameGuideUrl(){
        return '/huong-dan/'.$this->slug.'-'.$this->id;
    }

    public function getNewsUrl($categorySlug){
        return '/'.$categorySlug.'/'.$this->slug.'-'.$this->id;
    }

    public function findGameRelated($categorySlug){
        $game = App::make('myApp')->game;
        $myCategory = Category::where('alias', '=', $categorySlug)
            ->where('group_code', '=', Config::get('danhmuc.category-group-code.sub-article'))
            ->first();
        if(!$myCategory) throw new BusinessException('Tin: '.$this->title.' sai nhóm :'.$categorySlug);
        return $this->join('article_category', 'articles.id', '=', 'article_category.article_id')
            ->join('category', 'category.id', '=', 'article_category.category_id')
            ->join('game_articles', 'game_articles.article_id', '=', 'articles.id')
            ->where('game_articles.game_id', '=', $game->id )
            ->where('article_category.category_id', '=', $myCategory->id )
        ->where('articles.active','=', 1)->orderBy('articles.created_at','desc')
        ->limit(6)->get(array('articles.*', 'category.alias as category_alias', 'category.name as category_name'));

    }

    public function findNewsRelated($category){

        return $this->join('article_category', 'articles.id', '=', 'article_category.article_id')
            ->join('category', 'category.id', '=', 'article_category.category_id')
            ->where('article_category.category_id', '=', $category->id )
            ->where('articles.active','=', 1)->orderBy('articles.id','desc')
            ->limit(6)->get(array('articles.*', 'category.alias as category_alias', 'category.name as category_name'));

    }





}