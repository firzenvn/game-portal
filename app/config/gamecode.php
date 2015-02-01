<?php

return array(
    '12'=>array(
        'game_responses'=>array(
            'success_code'=>0,
            0=>  'Nạp thành công',
            -2=> 'Có lỗi khi liên kết đến kho dữ liệu',
            -3=>  'Nhân vật được nạp không tồn tại',
            -4=>  'Sever nạp tiền không tồn tại',
            -5=>  'Mã nạp tiền có lỗi',
            -6=>  'Đã tồn tại',
            -7=>  'business bất thường',
            -8=> 'Tham số nạp tiền có lỗi'
        ),
    ),
    '13'=>array(
        'success_code'=>1,
        'game_responses'=>array(
            1=>  'Nạp thành công',
            0=> 'Tham số chưa đủ',
            2=>  'Bên đối tác không tồn tại',
            3=>  '（0 < money <= 100,000）Giá trị vượt quá phạm vi cho phép',
            4=>  'chưa đăng nhập sever',
            5=>  'Nghiệm chứng thất bại',
            6=>  'Game không tồn tại',
            7=> 'Nhân vật không tồn tại',
            -7=> 'Mã order trung lặp, một mã tương tự đã sử dụng thành công',
            -1=> 'IP không nằm trong white list ',
            -4=> 'Nạp tiền thất bại',
            -102=> 'Nạp tiền bất thường, không có phản hồi',
            -999=> 'Nạp tiền bất thường, không có phản hồi',

        ),
    ),
    '14'=>array(
        'success_code'=>1,
        'game_responses'=>array(
            1=>  'Nạp thành công',
            2=>  'Mã giao dịch trùng',
            3=>  'Nhân vật không tồn tại',
            4=>  'Server không tồn tại',
            5=>  'Xác minh sai',
            6=>  'Lỗi nạp tiền',
            7=> 'Sai tham số',
            8=> 'Quá 30s, giao dịch thất bại',
            -999=> 'Nạp tiền bất thường, không có phản hồi',
        ),
    ),

    '20'=>array(
        'success_code'=>1,
        'game_responses'=>array(
            1=> 'thành công',
            2=> 'sai định dạng IP máy chủ',
            3=> 'Chưa có cấu hình server_ip ở api',
            4=> 'Lỗi time out',
            5=> 'trống username',
            6=> 'lỗi sign',
            7=> 'uname bị band ở api',
            8=> 'không tồn tại uname này  ở api',
            9=> 'chưa có nhân vật',
            10 => 'lỗi add knb hoặc lỗi mysql',
            11=>'Sai định dạng money'
        )
    )
)
?>