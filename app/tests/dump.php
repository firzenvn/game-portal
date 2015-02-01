<?php

date_default_timezone_set('Asia/Ho_Chi_Minh');
echo time(). PHP_EOL;
echo date('d/m/Y H:i:s', time()) . PHP_EOL;;
echo strtotime('2014-11-11 10:01:09').PHP_EOL;;

echo((strtotime('2014-11-14 23:01:09') - time())/60). PHP_EOL;;
