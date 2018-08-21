<?php
$lifetime=3600;
error_reporting(E_ERROR | E_PARSE);
session_start();
setcookie(session_name(),session_id(),time()+$lifetime);

require_once("./basicstrap.php");

$status = admin::ajax();
if(class_exists('pihome')&&(!(isset($status['code']))))
{
	$status = pihome::ajax();
}
print json_encode($status);
?>