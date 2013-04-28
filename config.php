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

define("SITE_URL", "http://" . $_SERVER['HTTP_HOST'] . "/dchub");
define("JS_URL", SITE_URL . "/js");
define("CSS_URL", SITE_URL . "/css");
define("IMAGE_URL", SITE_URL . "/img");
define("ACCOUNT_URL", SITE_URL . "/account");

define("NOTFOUND_URL", IMAGE_URL . "/not-found.jpg");

define("POSTERS_URL", SITE_URL . "/posters");
define("GENERAL_URL", POSTERS_URL . "/general");
define("PREMIUM_URL", POSTERS_URL . "/premium");
define("USERUPLOAD_URL", POSTERS_URL . "/useruploaded");
define("FEATURED_URL", POSTERS_URL . "/featured");

define("POSTERS_PATH", dirname(__FILE__) . "/posters");
define("GENERAL_PATH", POSTERS_PATH . "/general");
define("PREMIUM_PATH", POSTERS_PATH . "/premium");
define("USERUPLOAD_PATH", POSTERS_PATH . "/useruploaded");
define("FEATURED_PATH", POSTERS_PATH . "/featured");

define("PHPSCRIPTS_PATH", dirname(__FILE__) . "/php_scripts");

$ADMINS = array("sh.siddhartha@gmail.com");
$EXTNS = array("jpg", "jpeg", "png", "gif");

define("SQL_USER", "verlihub");     
define("SQL_PASS", "verlihub");      
define("SQL_DB", "verlihub");
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
