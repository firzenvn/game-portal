<?php


class RoleUser extends \Eloquent {

	public $timestamps = false;

	protected $fillable = array('role_id', 'user_id');

	protected $table = 'role_user';

	public static function boot()
	{
		parent::boot();

		RoleUser::saving(function($model)
		{
			$details='CHANGED DETAILS: <br/>';
			foreach($model->getDirty() as $attribute => $value){
				$original= $model->getOriginal($attribute);
				$details.="$attribute: '$original' => '$value'<br/>";
			}
			Activity::log(array(
				'contentID'   => $model->id,
				'contentType' => __CLASS__,
				'description' => 'Thêm/Cập nhật  một '.__CLASS__,
				'details'     => $details,
				'updated'     => true,
			));
		});
	}
}