<?php
/**
 * Blameable auditing support for Laravel's Eloquent ORM
 *
 * @author Ross Masters <ross@rossmasters.com>
 * @copyright Ross Masters 2013
 * @license MIT
 */

namespace Util\Model\Traits;

use User;

/**
 * Add event-triggered references to the authorised user that triggered them
 *
 * @property \Illuminate\Database\Eloquent\Model $deleted_by The deleter of this model
 * @property int $deleted_by_id User id of the model deleter
 */
trait DeletedBy
{
    /**
     * Get the user that deleted the model
     * @return \Illuminate\Database\Eloquent\Model User instance
     */
    public function deletedBy()
    {
        $model = new User();
        return $this->belongsTo($model)->withTrashed();
    }
}