<?php

class Role extends \Eloquent {
	protected $fillable = [];

	public function permissions() {
		return $this->hasMany('Permission');
	}

	public function inherited_role(){
		return $this->belongsTo('Role','inherited_roleid');
	}

	public static function boot()
	{
		parent::boot();

		Role::saving(function($model)
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