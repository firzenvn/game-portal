<?php

class ActivityLog extends \Eloquent {
	protected $fillable = [];
	protected $table='activity_log';

	public function user(){
		return $this->belongsTo('User','user_id');
	}

	public static function boot()
	{
		parent::boot();

		ActivityLog::deleting(function($model)
		{
			return FALSE;
		});
	}
}