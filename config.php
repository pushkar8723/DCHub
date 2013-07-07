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
displayErrors(TRUE);

define("SITE_URL", "http://" . $_SERVER['HTTP_HOST'] . "/DCHub");
define("PUBLIC_URL", SITE_URL . "/public");
define("JS_URL", PUBLIC_URL . "/js");
define("CSS_URL", PUBLIC_URL . "/css");
define("IMAGE_URL", PUBLIC_URL . "/images");

session_set_cookie_params (0, substr(SITE_URL, strlen("http://" . $_SERVER['HTTP_HOST'])));
session_start();
clearstatcache();

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
$motdfile = '/home/administrator/Documents/motd';
$classmap = array(0 => 0, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 7 => 1, 8 => 1, 9 => 3, 10 => 10);
$restrictednicks = array('hubbot', 'opchat', 'dj', 'sourcecode');
$class = array(0 => 'Novice', 1 => 'Experienced', 2 => 'Famous', 3 => 'Moderator', 4 => 'Pseudo-Admin', 8=> 'Master', 9 => 'Cheef', 10 => 'Admin');
$defaultGroup = array('2010' => '2k10', '2011' => '2k11', '2012' => '2k12', '2013' => '2k13');
$categories = array('Everything' => '', "Movies" => 'movie', "TV Series" => 'tv', "Books" => 'book', 'Games' => 'game');
require_once 'functions.php';
require_once 'components.php';
?>
