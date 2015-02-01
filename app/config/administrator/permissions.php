<?php

/**
 * Actors model config
 */

return array(

	'title' => 'Quyền',

	'single' => 'quyền',

	'model' => 'Permission',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'role' => array(
			'title' => 'Vai trò',
			'relationship' => "role",
			'select' => "role_name",
		),
		'type' => array(
			'title' => 'Quyền',
			'select' => "type",
		),
		'action' => array(
			'title' => 'Hành động',
			'select' => "action",
		),
		'resource_name' => array(
			'title' => 'Tên tài nguyên',
			'relationship' => "resource_name",
			'select' => "name",
		),
		'resource' => array(
			'title' => 'URI tài nguyên',
			'select' => "resource",
		),
		'resource_id' => array(
			'title' => 'ID tài nguyên',
			'select' => "resource_id",
		),
	),

	/**
	 * The filter set
	 */
	'filters' => array(
		'role' => array(
			'title' => 'Vai trò',
			'type' => 'relationship',
			'name_field'=> "role_name"
		),
		'resource_name' => array(
			'title' => 'Tài nguyên',
			'type' => 'relationship',
			'name_field'=> "name"
		),
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'role' => array(
			'title' => 'Vai trò',
			'type' => 'relationship',
			'name_field'=> "role_name"
		),
		'resource_name' => array(
			'title' => 'Tài nguyên',
			'type' => 'relationship',
			'name_field'=> "name",
		),
		'action' => array(
			'title' => 'Hành động',
			'type' => 'enum',
			'options' => array('access'),
			'value'=>'access',
		),
		'type' => array(
			'title' => 'Quyền',
			'type' => 'enum',
			'options' => array('allow'),
			'value'=>'allow',
		),
	),

);