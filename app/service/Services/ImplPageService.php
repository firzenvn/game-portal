<?php
namespace Services;



use EModel\Pages;
use Util\Exceptions\EntityNotFoundException;

class ImplPageService extends BaseService implements PageService
{


    /**
     * @param $id
     * @return Pages
     */
    public function requireById($id)
    {
        $model = Pages::find($id);

        if ( ! $model) {
            throw new EntityNotFoundException;
        }
        return $model;
    }

    public function getAllPageWithTemplate()
    {
        return Pages::with('template')->get();
    }
}