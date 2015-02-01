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
 * @property \Illuminate\Database\Eloquent\Model $created_by The creator of this model
 * @property int $created_by_id User id of the model creator
 */
trait CreatedBy
{
    /**
     * Get the user that created the model
     * @return \Illuminate\Database\Eloquent\Model User instance
     */
    public function createdBy()
    {
        $model = new User();
        return $this->belongsTo($model, 'created_by')->withTrashed();
    }
}