<?php

/**
 * Actors model config
 */


return array(

    'title' => 'Vòng quay may mắn',

    'single' => 'vqmm',

    'model' => 'SpinTurn',

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
        'result'=>array(
            'title'=>'Kết quả',
            'select'=>'result'
        ),
        'amount'=>array(
            'title'=>'Đặt cược'
        ),
        'bonus_amount'=>array(
            'title'=>'Trúng thưởng'
        ),
        'description'=>array(
            'title'=>'Mô tả',
            'select'=>'description'
        ),
        'status'=>array(
            'title'=>'Trạng thái(200:ok)'
        ),
        'created_at' => array(
            'title' => 'Thời gian'
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
    ),


);