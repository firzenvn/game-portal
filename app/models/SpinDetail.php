<?php

class SpinDetail extends \Eloquent {
    protected $table = 'spin_details';
	protected $fillable = ['spin_turn_id', 'slot', 'amount'];
}