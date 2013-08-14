<?php
define('SERVER_ROOT', dirname(dirname(__FILE__)).'/app');
header('Access-Control-Allow-Origin: *');
include_once (SERVER_ROOT . '/controllers/router.php');
?>
