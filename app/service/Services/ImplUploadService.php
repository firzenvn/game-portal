<?php
namespace Services;

use EModel\Uploads;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Util\CommonHelper;
use Util\Exceptions\SystemException;

class ImplUploadService extends BaseService implements UploadService
{

    /**
     * Get all uploads in order of upload
     * @return Uploads
     */
    public function getInOrder()
    {
        return $this->orderBy('order','asc')->get();
    }


    /**
     * Set the order of the ID's from 0 to the array length passed in
     * @param array $ids The Upload IDs
     */
    public function setOrder( $ids ){

        // Don't do anything if nothing is passed in
        if(!$ids)
            return;

        // Set single integer to arrays
        if( !is_array($ids) )
            $ids = [ $ids ];

        // Loop through each id and update the database accordingly
        foreach($ids as $order=>$id){
            Uploads::where('id','=',$id)->update( [ 'order'=>$order ] );
        }

        return true;
    }

    /**
     * Delete an upload by it's database ID
     * @param  mixed[integer|array]     $id     The database ID
     * @return boolean                          True if deleted
     */
    public function deleteById( $id ){
        if( !is_array($id) )
            $id = array( $id );

        // Delete The Items From The File Store
        $this->physicallyDelete( Uploads::whereIn( 'id' , $id )->get() );

        // Now delete the items from the database
        Uploads::whereIn( 'id' , $id )->delete();

        return true;
    }


    public function deleteByIdType( $id , $uploadableType ){
        // Delete the images directory for these types / links
        $base_path = Config::get('common.upload_base_path');
     /*   $toDelete  = public_path().'/'.$base_path.$type.'/'.$id;
        File::deleteDirectory( $toDelete );*/

        // Now return the result of deleting all the records that match
        return Uploads::where('uploadable_type','=',$uploadableType)->where('uploadable_id','=',$id)->delete();
    }

    public function deleteByIdTypeType( $id , $uploadableType, $type ){
        return Uploads::where('uploadable_type','=',$uploadableType)
            ->where('uploadable_id','=',$id)
            ->where('type','=',$type)
            ->delete();
    }

    /**
     * Physically delete all files related to the uploads collection passed in
     * @return boolean
     */
    private function physicallyDelete( $uploads ){

        // Return false if we have no uploads passed in
        if( !$uploads )
            return false;

        // Loop through each upload object
        foreach($uploads as $upload){
            // If the original file actually exists that is specified in the DB, then lets delete if
            if( File::isFile( $upload->getAbsoluteSrc() ) )
                File::delete( $upload->getAbsoluteSrc() );
        }

        return true;
    }

    /**
     * Upload an image to an object type and ID along with key
     * @param  integer $id   The ID of the object to associate with
     * @param  string  $type The class name of the model to associate with
     * @param  string  $key  The key used in the directory heirachy
     * @return void
     */
    public function save( $id , $uploadType ,  $webPath, $type )
    {

        $this->deleteByIdTypeType($id, $uploadType, $type);
        if(!File::isFile(public_path().$webPath))
            throw new SystemException('File '.$webPath.' không tồn tại');
        $filename = basename(public_path().$webPath);
        $extension = CommonHelper::getExtensions($filename);
        $now = date('Y-m-d H:i:s');
        $upload = new Uploads(array(
            'uploadable_type'   =>  $uploadType,
            'uploadable_id'     =>  $id,
            'path'              =>  $webPath,
            'filename'          =>  $filename,
            'extension'         =>  $extension,
            'order'             =>  999,
            'type'              =>  $type,
            'created_at'        =>  $now,
            'updated_at'        =>  $now));
        $upload->save();

    }
}