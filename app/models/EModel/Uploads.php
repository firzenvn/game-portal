<?php namespace EModel;
use Config;
use Util\ImgHelper;
use Util\Model\EloquentBaseModel;

class Uploads extends EloquentBaseModel
{

    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'uploads';

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array('uploadable_type',
        'uploadable_id'  ,
        'path'         ,
        'filename'   ,
        'extension'    ,
        'order'     ,
        'type'     ,
        'created_at'    ,
        'updated_at'   );

    protected $validationRules = [];

    /**
     * Strip the extensions from the filename and just return the filename, we need this to append stuff
     * @param  string $filename The filename to strip
     * @return string
     */
    public function stripExtensions( $filename ){
        return preg_replace("/\\.[^.\\s]{3,4}$/", "", $filename);
    }


    /**
     * Size up the current record and return the resulting filename
     * @param  integer  $width  The width of the resulting image
     * @param  integer  $height The height of the resulting image
     * @param  boolean  $crop   Decide whether to crop the image or not
     * @return string           The sized up stored resulting image
     */
    public function sizeImg( $width , $height , $crop = true ){
        // Get our image helper, pass in requirements and get our new image filename
        $helper = new ImgHelper( $this );
        $helper->width = $width;
        $helper->height = $height;
        $helper->crop = $crop;

        return $helper->get();
    }

    /**
     * Get the usable src (public path and filename)
     * @return string
     */
    public function getSrc(){
        return $this->path;
    }

    /**
     * Get the absolute usable src ( /var/www/vhosts/domain.com/public/uploads/products/filename.jpg etc )
     * @return string
     */
    public function getAbsoluteSrc(){
        return public_path().$this->path;
    }

    public function getAbsolutePath(){
        return basename($this->getAbsoluteSrc());
    }
    /*public function getAbsolutePath(){
        $base_path = Config::get('common.upload_base_path');
        return public_path().'/'.$base_path.$this->path.'/'.$this->uploadable_id.'/';
    }*/

/*    public function getPath(){
        return url( $this->path.'/'.$this->uploadable_id.'/' );
    }*/

}
