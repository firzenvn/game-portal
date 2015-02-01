<?php

/**
 * Actors model config
 */

return array(

	'title' => 'Vai trò',

	'single' => 'vai trò',

	'model' => 'Role',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'role_name' => array(
			'title' => 'Tên vai trò',
			'select' => "role_name",
		),
		'inherited_role' => array(
			'title' => 'Thừa kế vai trò',
			'relationship' => 'inherited_role',
			'select' => "(:table).role_name",
		),
	),

	/**
	 * The filter set
	 */
	'filters' => array(
		'id',
		'role_name' => array(
			'title' => 'Tên vai trò',
		),
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'role_name' => array(
			'title' => 'Tên',
			'type' => 'text',
		),
		'inherited_role' => array(
			'title' => 'Thừa kế vai trò',
			'type' => "relationship",
			'name_field'=> "role_name"
		),
	),

);