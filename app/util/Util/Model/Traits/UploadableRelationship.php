<?php namespace Util\Model\Traits;
use App;

trait UploadableRelationship
{

    /**
     * The relationship setup for taggable classes
     * @return Eloquent
     */
    public function uploads()
    {
        return $this->morphMany( 'EModel\Uploads' , 'uploadable' )->orderBy('order','asc');
    }

    public function getUploadByType($type){
        $resultArr = array();
        $allUploads = $this->uploads;
        foreach ($allUploads as $aUpload) {
            if($aUpload->type == $type)
                array_push($resultArr,$aUpload );
        }
        return $resultArr;
    }

    /**
     * Remove the imagery associated with this model
     * @return void
     */
    public function deleteImagery($id)
    {
        $uploads = App::make('Services\UploadService');
        $uploads->deleteById( $id );
    }

    /**
     * Remove the imagery associated with this model
     * @return void
     */
    public function deleteAllImagery()
    {
        $uploads = App::make('Services\UploadService');
        $uploads->deleteByIdType( $this->id , get_class($this) );
    }

}