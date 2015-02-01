<?php
/**
 * Blameable auditing support for Laravel's Eloquent ORM
 *
 * @author Ross Masters <ross@rossmasters.com>
 * @copyright Ross Masters 2013
 * @license MIT
 */

namespace Util\Model\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class BlamableObserver
{
    /** @var array $fields Mapping of events to fields */
    private $fields;

    // Default field names for states
    protected static $defaultFields = array(
        'created' => 'created_by',
        'updated' => 'updated_by',
        'deleted' => 'deleted_by',
    );

    /**
     * Creating event
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function creating($model)
    {
        $this->updateBlamables($model);
    }

    /**
     * Updating event
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function updating($model)
    {
        $this->updateBlamables($model);
    }

    /**
     * Deleting event
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function deleting($model)
    {
        $this->updateDeleteBlamable($model);
    }

    /**
     * Update the blamable fields
     */
    protected function updateBlamables($model)
    {
        $user = $this->activeUser();

        if ($user) {
            // Set updated-by if it has not been touched on this model
            if ($this->isBlamable($model, 'updated') && !$model->isDirty($this->getColumn($model, 'updated'))) {
                $this->setUpdatedBy($model, $user);
            }

            // Set created-by if the model does not exist
            if ($this->isBlamable($model, 'created') && !$model->exists && !$model->isDirty($this->getColumn($model, 'created'))) {
                $this->setCreatedBy($model, $user);
            }
        }
    }

    /**
     * Update the deletedBy blamable field
     */
    public function updateDeleteBlamable($model)
    {
        $user = $this->activeUser();

        if ($user) {
            // Set deleted-at if it has not been touched
            if ($this->isBlamable($model, 'deleted') && !$model->isDirty($this->getColumn($model, 'deleted'))){
                $this->setDeletedBy($model, $user);
                $model->save();
            }
        }
    }

    /**
     * Get the active user
     *
     * @return int User ID
     */
    protected function activeUser()
    {
       /* $fn = Config::get('culpa::users.active_user');
        if (!is_callable($fn)) {
            throw new \Exception("culpa::users.active_user should be a closure");
        }

        return $fn();*/
        return Auth::check() ? Auth::user()->id : null;
    }

    /**
     * Set the created-by field of the model
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param int $user
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function setCreatedBy($model, $user)
    {
        $model->{$this->getColumn($model, 'created')} = $user;
        return $model;
    }

    /**
     * Set the updated-by field of the model
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param int $user
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function setUpdatedBy($model, $user)
    {
        $model->{$this->getColumn($model, 'updated')} = $user;
        return $model;
    }

    /**
     * Set the deleted-by field of the model
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param int $user
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function setDeletedBy($model, $user)
    {
        $model->{$this->getColumn($model, 'deleted')} = $user;
        return $model;
    }

    /**
     * Get the created/updated/deleted-by column, or null if it is not used
     *
     * @param string $event One of (created|updated|deleted)
     * @return string|null
     */
    public function getColumn($model, $event) {
        if (array_key_exists($event, $this->getBlamableFields($model))) {
            $fields = $this->getBlamableFields($model);
            return $fields[$event];
        } else {
            return null;
        }
    }

    /**
     * Does the model use blamable fields for an event?
     *
     * @param string $event One of (created|updated|deleted), or omitted for any
     * @return bool
     */
    public function isBlamable($model, $event = null)
    {
        return $event ?
            array_key_exists($event, $this->getBlamableFields($model)) :
            count($this->getBlamableFields($model)) > 0;
    }

    /**
     * Evaluate the blamable fields to use, using reflection to find a protected $blamable property
     *
     * If keys in $blamable exist for any of [created, updated, deleted], the
     * values are taken as the column names.
     *
     * If values exist for any of [created, updated, deleted], the default
     * column names are used ($defaultFields in the method below).
     *
     * Examples:
     *   private $blamable = ['created', 'updated'];
     *   private $blamable = ['created' => 'author_id'];
     *   private $blamable = ['created', 'updated', 'deleted' => 'killedBy'];
     *
     * @param array|null $fields Optionally, the $blamable array can be given rather than using reflection
     * @return array
     */
    public static function findBlamableFields($model, $blamable = null)
    {
        if (is_null($blamable)) {
            // Get the reflected model instance in order to access $blamable
            $reflectedModel = new \ReflectionClass($model);

            // Check if options were passed for blameable
            if ($reflectedModel->hasProperty('blamable')) {
                // Get the protected $blamable property
                $blamableProp = $reflectedModel->getProperty('blamable');
                $blamableProp->setAccessible(true);

                $blamable = $blamableProp->getValue($model);
            } else {
                // Model doesn't have a property for $blamable
                return array();
            }
        }

        $fields = array();
        if (is_array($blamable)) {
            // Created
            if (array_key_exists('created', $blamable)) {
                // Custom field name given
                $fields['created'] = $blamable['created'];
            } else if (in_array('created', $blamable)) {
                //  Use the default field name
                $fields['created'] = self::$defaultFields['created'];
            }

            // Updated
            if (array_key_exists('updated', $blamable)) {
                // Custom field name given
                $fields['updated'] = $blamable['updated'];
            } else if (in_array('updated', $blamable)) {
                //  Use the default field name
                $fields['updated'] = self::$defaultFields['updated'];
            }

            // Deleted
            if (array_key_exists('deleted', $blamable)) {
                // Custom field name given
                $fields['deleted'] = $blamable['deleted'];
            } else if (in_array('deleted', $blamable)) {
                //  Use the default field name
                $fields['deleted'] = self::$defaultFields['deleted'];
            }
        }

        return $fields;
    }

    /**
     * Get the blamable fields
     * @return array
     */
    protected function getBlamableFields($model)
    {
        if (isset($this->fields)) {
            return $this->fields;
        }

        $this->fields = self::findBlamableFields($model);

        return $this->fields;
    }
}