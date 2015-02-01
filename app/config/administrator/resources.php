<?php

/**
 * Actors model config
 */

return array(

	'title' => 'Tài nguyên',

	'single' => 'tài nguyên',

	'model' => 'Resource',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id' => array(
			'title' => 'ID',
			'select' => "id",
		),
		'uri' => array(
			'title' => 'URI',
			'select' => "uri",
		),
		'name' => array(
			'title' => 'Tên vai trò',
			'select' => "name",
		),
	),

	/**
	 * The filter set
	 */
	'filters' => array(
		'id' => array(
			'title' => 'ID',
		),
		'uri' => array(
			'title' => 'URI',
		),
		'name' => array(
			'title' => 'Tên tài nguyên',
		),
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'uri' => array(
			'title' => 'URI',
			'type' => 'text',
		),
		'name' => array(
			'title' => 'Tên tài nguyên',
			'type' => "text",
		),
	),

);