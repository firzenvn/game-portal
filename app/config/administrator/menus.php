<?php

/**
 * Actors model config
 */

return array(

	'title' => 'Menu',

	'single' => 'menu',

	'model' => 'Menu',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'parent_menu' => array(
			'title' => 'Menu cha',
			'relationship' => 'parent_menu',
			'select' => "(:table).name",
		),
		'name',
		'uri',
		'order_number',
		'class',
		'active',
	),

	/**
	 * The filter set
	 */
	'filters' => array(
		'id',
		'parent_id',
		'name',
		'uri',
		'active',
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'id',
		'parent_menu' => array(
			'title' => 'Menu cha',
			'type' => "relationship",
			'name_field'=> "name"
		),
		'name',
		'uri',
		'order_number',
		'class',
		'active',
	),

);