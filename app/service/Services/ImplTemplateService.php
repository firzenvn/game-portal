<?php
namespace Services;



use EModel\Templates;
use Util\Exceptions\EntityNotFoundException;

class ImplTemplateService extends BaseService implements TemplateService
{


    /**
     * @param $id
     * @return Templates
     */
    public function requireById($id)
    {
        $model = Templates::find($id);

        if ( ! $model) {
            throw new EntityNotFoundException;
        }
        return $model;
    }
}