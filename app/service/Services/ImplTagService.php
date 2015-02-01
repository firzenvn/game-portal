<?php
namespace Services;

use EModel\Tags;
use Util\CommonHelper;

class ImplTagService extends BaseService implements TagService
{

    public function deleteByIdType( $id , $taggableType ){
        return Tags::where('taggable_type','=',$taggableType)->where('taggable_id','=',$id)->delete();
    }

    public function save( $id , $tagType ,  $tag )
    {
        $tag = new Tags(array('tag'=>$tag, 'taggable_id'=>$id,
            'taggable_type'=>$tagType, 'alias'=>CommonHelper::vietnameseToASCII($tag)));
        $tag->save();
    }

}