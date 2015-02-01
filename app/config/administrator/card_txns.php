<?php

/**
 * Actors model config
 */


return array(

    'title' => 'Nạp Thẻ',

    'single' => 'giao dịch',

    'model' => 'CardTxn',

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
        'card_type_name'=>array(
            'title'=>'Loại thẻ',
        ),
        'pay_amount'=>array(
            'title'=>'Số tiền',
            'select'=>'pay_amount'
        ),
        'statusmsg'=>array(
            'title'=>'Trạng thái',
        ),
        'game_responsemsg'=>array(
            'title'=>'Trạng thái game'
        ),
        'created_at' => array(
            'title' => 'Thời gian'
        ),
        'ref_txn_id'=>array(
            'title'=>'Mã giao dịch'
        ),
        'pin'=>array(
            'title'=>'Mã thẻ',
            'select'=>'pin'
        ),
        'seri'=>array(
            'title'=>'Seri',
            'select'=>'seri'
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
            'num_options' => 5, //default is 10
            'name_field'=>"username"
        ),
        'game'=>array(
            'title'=>'Game',
            'type'=>'relationship',
            'name_field'=>"name"
        ),
        'card_type'=>array(
            'title'=>'Loại thẻ',
            'type'=>'enum',
            'options'=>Config::get('common.card_types'),
        ),
        'ref_txn_id'=>array(
            'title'=>'Mã giao dịch'
        ),
        'pin'=>array(
            'title'=>'Mã thẻ',
            'type'=>'text',
        ),
        'seri'=>array(
            'title'=>'Seri',
            'type'=>'text'
        ),
        'status'=>array(
            'title'=>'Trạng thái',
            'type'=>'enum',
            'options'=>Config::get('common.txn_status')
        ),
        'game_response'=>array(
            'title'=>'Trạng thái game',
            'type'=>'enum',
            'options'=>Config::get('common.game_responses')
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
        'ref_txn_id'=> array(
            'title'=>'Mã giao dịch',
            'editable'=>false
        ),
        'pin'=>array(
            'title'=>'Mã thẻ',
            'editable'=>false
        ),
        'seri'=>array(
            'title'=>'Số Seri',
            'editable'=>false
        ),
        'created_at'=>array(
            'title'=>'Thời gian',
            'editable'=>false
        )

    ),


);