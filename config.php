<?php

define("DEBUG", true);

function displayErrors($option = true) {
  if ($option) {
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', '1');
  }
  else {
    error_reporting(0);
    ini_set('display_errors', '0');
  }
}

displayErrors(DEBUG);

session_start();
clearstatcache();

define("SITE_URL", "http://" . $_SERVER['HTTP_HOST'] . "/DCHub");
define("PUBLIC_URL", SITE_URL . "/public");
define("JS_URL", PUBLIC_URL . "/js");
define("CSS_URL", PUBLIC_URL . "/css");
define("IMAGE_URL", PUBLIC_URL . "/images");

define("SQL_USER", "verlihub2");     
define("SQL_PASS", "diss73");      
define("SQL_DB", "verlihub2");
define("SQL_HOST", "localhost");
define("SQL_PORT", "3306");

define("MAIL_PATH", "Mail.php");
//define("MAIL_USER", "");
//define("MAIL_PASS", "");
//define("MAIL_HOST", "");    // ssl://smtp.gmail.com
//define("MAIL_PORT", "");    // 465


define("ERROR_LOG", dirname(__FILE__) . "/errors.txt");

date_default_timezone_set("Asia/Kolkata");

require_once 'functions.php';
require_once 'components.php';
?>
