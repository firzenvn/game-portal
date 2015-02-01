<?php

class Permission extends \Eloquent {
	protected $fillable = [];

	public function role(){
		return $this->belongsTo('Role');
	}
	public function resource_name(){
		return $this->belongsTo('Resource','resource_id');
	}

	public static function boot()
	{
		parent::boot();

		Permission::saving(function($model)
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