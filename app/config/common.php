<?php
/**
 * Created by PhpStorm.
 * User: Lê Trọng Dương
 * Date: 6/21/14
 * Time: 9:58 AM
 * To change this template use File | Settings | File Templates.
 */

return array(
    'admin_url_segment'=>'admin',
    'vendor'=>'Satisfied Commitment',
    'upload_base_path'=>'uploads/',
    'card_amounts'=>array(
        '100'=>'100',
        '200'=>'200',
        '500'=>'500',
        '1000'=>'1,000',
        '2000'=>'2,000',
        '5000'=>'5,000'
    ),
    'txn_status'=>array(
        200=>'Thành công',
        406=>'Thất bại'
    ),
    'game_responses'=>array(
        0=>  'Nạp thành công',
        -2=> 'Có lỗi khi liên kết đến kho dữ liệu',
        -3=>  'Nhân vật được nạp không tồn tại',
        -4=>  'Sever nạp tiền không tồn tại',
        -5=>  'Mã nạp tiền có lỗi',
        -6=>  'Đã tồn tại',
        -7=>  'business bất thường',
        -8=> 'Tham số nạp tiền có lỗi'
    ),
    'card_types'=>array(
        'VTE'=>'Viettel',
        'VMS'=>'Mobifone',
        'VNP'=>'Vinaphone'
    ),
    'game_leagues_level_range'=>array(
        'bado'=>array(
            '40-50'=>'40-50',
            '51-60'=>'51-60',
            '61-70'=>'61-70'
        )
    ),
    'game_leagues_round'=>array(
        'bado'=>array(
            '1'=>'Vòng 1/8',
            '2'=>'Vòng tứ kết',
            '4'=>'Vòng bán kết',
            '8'=>'Vòng chung kết',
        )
    ),
    'bet_propotion'=>5,

    'giftcode_apply_for' => array(
        null=>'Maxgate',
        \Util\CommonConstant::GIFT_CODE_FACEBOOK=>'Facebook'
    )

//--------admin menu item, should be replaced by data retrieved from database?---
    /*'menu_items'=>array(
        'articles'=>array(
            'name'=>'Articles',
            'icon'=>'list',
            'top'=>true,
            'url'=>'/admin/articles',
        ),
        'catalogs'=>array(
            'name'=>'Danh mục',
            'icon'=>'book',
            'top'=>true,
            'items'=>array(

                'article_group'=>array(
                    'name'=>'Nhóm chính bài viết',
                    'url'=>'/admin/catalogs/categories/ARTICLE',
                ),

            )
        ),
        'cms'=>array(
            'name'=>'CMS',
            'icon'=>'book',
            'top'=>true,
            'items'=>array(
                'template'=>array(
                    'name'=>'Template',
                    'url'=>'/admin/templates',
                ),
                'page'=>array(
                    'name'=>'Page',
                    'url'=>'/admin/pages',
                ),
            )
        ),
        'blocks'=>array(
            'name'=>'Content Blocks',
            'icon'=>'th-large',
            'top'=>true,
            'url'=>'/admin/blocks',
        ),
        'galleries'=>array(
            'name'=>'Galleries',
            'icon'=>'picture',
            'top'=>true,
            'url'=>'/admin/galleries',
        ),
        'users'=>array(
            'name'=>'Users',
            'icon'=>'user',
            'top'=>true,
            'url'=>'/admin/users',
        ),
        'settings'=>array(
            'name'=>'Settings',
            'icon'=>'cog',
            'top'=>true,
            'url'=>'/admin/settings',
        ),


    )*/
);