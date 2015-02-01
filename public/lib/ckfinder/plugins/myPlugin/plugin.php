<?php
/*
* CKFinder
* ========
* http://ckfinder.com
* Copyright (C) 2007-2012, CKSource - Frederico Knabben. All rights reserved.
*
* The software, this file and its contents are subject to the CKFinder
* License. Please read the license.txt file before using, installing, copying,
* modifying or distribute this file or part of its contents. The contents of
* this file is part of the Source Code of CKFinder.
*
* CKFinder extension: adds watermark to uploaded images.
*/

class CoreFile
{
    function onAfterFileUpload($currentFolder, $uploadedFile, $sFilePath)
    {
        $resourceService = new ResourceService();
        $data = array("type_id"=>1, "path"=>$sFilePath);
        $resourceService->addResource($data);
        return true;
    }


}

$watermark = new CoreFile();
$config['Hooks']['AfterFileUpload'][] = array($watermark, 'onAfterFileUpload');

