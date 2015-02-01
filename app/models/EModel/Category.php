<?php namespace EModel;


use Util\Model\EloquentBaseModel;

class Category extends EloquentBaseModel
{

    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'category';

    public $timestamps = false;

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array('code', 'name', 'description', 'lft', 'rgt', 'group_code', 'alias');

    protected $validationRules = [
        'code'    => 'required|min:2|max:15|alpha_dash|unique:category,code,<id>',
        'name'    => 'required|min:2',
        'group_code'    => 'required|min:2',

    ];


    public function getDirectParent(){
        $ancestorRows = $this->where('lft', '<', $this->lft)
            ->where('rgt', '>', $this->rgt)
            ->where('group_code', '=', $this->group_code)
            ->get();


        if($ancestorRows->count() == 0)
            return null;
        $minLft = 0;
        $result = null;
        foreach ($ancestorRows as $ancestor) {
            if($ancestor->lft > $minLft){
                $minLft = $ancestor->lft;
                $result = $ancestor;
            }
        }
        return $result;
    }

    public function getAllChildren(){
        return $this->where('lft', '>', $this->lft)
            ->where('rgt', '<', $this->rgt)
            ->where('group_code', '=', $this->group_code)
            ->orderBy('lft')
            ->get();
    }

    public function isLeaf(){
        if($this->lft == $this->rgt -1)
            return true;
        return false;
    }

    public function upMyPosition(){
        $upperBrother = $this->where('rgt', '=', $this->lft - 1)
            ->where('group_code', '=', $this->group_code)
            ->first();

        $leftDelta = $this->lft - $upperBrother->lft;
        $rightDelta =  $this->rgt - $upperBrother->rgt;
        $myChildren = $this->getAllChildren();
        $upperBrotherTree = $upperBrother->getAllChildren();
        foreach ($myChildren as $item) {
            $this->where('id', '=' , $item->id)->
                update(array('lft'=>$item->lft - $leftDelta, 'rgt'=>$item->rgt - $leftDelta));
        }
        $this->where('id', '=' , $this->id)->
            update(array('lft'=>$this->lft - $leftDelta, 'rgt'=>$this->rgt - $leftDelta));

        foreach ($upperBrotherTree as $item) {
            $this->where('id', '=' , $item->id)->
                update(array('lft'=>$item->lft + $rightDelta, 'rgt'=>$item->rgt + $rightDelta));
        }

        $this->where('id', '=' , $upperBrother->id)->
            update(array('lft'=>$upperBrother->lft + $rightDelta, 'rgt'=>$upperBrother->rgt + $rightDelta));
    }

    public function downMyPosition(){
        $lowerBrother = $this->where('lft', '=', $this->rgt + 1)
            ->where('rgt', '>', $this->rgt)
            ->where('group_code', '=', $this->group_code)
            ->first();
        $lowerBrother->upMyPosition();
    }

    public function articles()
    {
        return $this->belongsToMany('EModel\Articles', 'article_category', 'category_id',  'game_id');
    }

    public function games()
    {
        return $this->belongsToMany('EModel\Games', 'game_categories', 'category_id', 'game_id');
    }
}
