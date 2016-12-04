<?php 

//vui lòng không xóa những thông số này
define("APPLICATION_PATH","application/");
define("SYSTEM_PATH","library/");
define("EX_VIEW_FILE",".phtml");
define("ASSET_FOLDER","public");

date_default_timezone_set('Asia/Ho_Chi_Minh');

require SYSTEM_PATH."application.php";

$app=new Application();
$app->run(require "config/config.php");