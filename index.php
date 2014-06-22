<?php
error_reporting ( E_ALL | E_STRICT );
ini_set ( 'display_errors', 'on' );
date_default_timezone_set ( 'Asia/Shanghai' );
mb_internal_encoding ( 'utf-8' );
define ( 'DEBUG', FALSE );
header ( "Content-Type:text/html;charset=utf-8" );
echo "test thrift";
