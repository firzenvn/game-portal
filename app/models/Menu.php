<?php

class Menu extends \Eloquent {
	protected $fillable = [];
	public $timestamps = false;

	public function parent_menu(){
		return $this->belongsTo('Menu','parent_id');
	}

	public static function boot()
	{
		parent::boot();

		Menu::saving(function($model)
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