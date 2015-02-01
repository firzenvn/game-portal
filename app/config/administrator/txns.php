<?php

/**
 * Actors model config
 */


return array(

    'title' => 'Nạp Xu',

    'single' => 'giao dịch',

    'model' => 'Txn',

    /**
     * The display columns
     */
    'columns' => array(
        'id',
        'user'=>array(
            'title'=>'User',
            'relationship'=>'user',
            'select'=>'username'
        ),
        'game'=>array(
            'title'=>'Game',
            'relationship'=>'game',
            'select'=>'name'
        ),
        'server'=>array(
            'title'=>'Server',
            'relationship'=>'game_server',
            'select'=>"concat('S',order_number,'-',name)",
        ),
        'game_amount'=>array(
            'title'=>'Số Xu',
            'select'=>'game_amount'
        ),
        'status'=>array(
            'title'=>'Trạng thái(200:ok)'
        ),
        'game_response'=>array(
            'title'=>'Trạng thái game'
        ),
        'created_at' => array(
            'title' => 'Thời gian'
        ),
        'description'=>array(
            'title'=>'Mô tả',
            'select'=>'description'
        ),
        'ref_txn_id'=>array(
            'title'=>'Mã giao dịch'
        ),
    ),

    /**
     * The filter set
     */
    'filters' => array(
        'id'=>array(
            'title'=>'ID'
        ),
        'user'=>array(
            'title'=>'Username',
            'type'=>'relationship',
            'autocomplete' => true,
            'num_options' => 5,
            'name_field'=>"username"
        ),
        'game'=>array(
            'title'=>'Game',
            'type'=>'relationship',
            'name_field'=>"name"
        ),
        'ref_txn_id'=>array(
            'title'=>'Mã giao dịch'
        ),
        'status'=>array(
            'title'=>'Trạng thái',
            'type'=>'enum',
            'options'=>Config::get('common.txn_status')
        ),
        'created_at' => array(
            'title' => 'Thời gian',
            'type' => 'date',
        ),
    ),

    /**
     * The editable fields
     */
    'edit_fields' => array(
        'id' => array(
            'title' => 'Id'
        ),
        'ref_txn_id'=>array(
            'title'=>'Mã giao dịch',
            'editable'=>false
        ),
        'description'=>array(
            'title'=>'Mô tả',
            'editable'=>false
        ),
        'created_at'=>array(
            'title'=>'Thời gian',
            'editable'=>false
        )
    ),


);