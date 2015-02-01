<?php

namespace Services;




interface TagService {



    /**
     * Delete an tag by it's type and link ID
     * @param  integer     $id     The link record ID
     * @param  integer     $type   The link type
     * @return boolean             True if deleted
     */
    public function deleteByIdType( $id , $type );


    /**
     * Upload an image to an object type and ID along with key
     * @param  integer $id   The ID of the object to associate with
     * @param  string  $type The class name of the model to associate with
     * @param  string  $key  The key used in the directory heirachy
     * @return void
     */
    public function save( $id , $uploadType ,  $tag );

}