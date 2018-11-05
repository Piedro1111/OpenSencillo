<?php
error_reporting(E_ERROR | E_PARSE);
session_start();

require_once("./basicstrap.php");

$status = new admin('ajax.slot.php');
$out = $status->ajax();
if(class_exists('pihome')&&(!(isset($out['code']))))
{
	$out = pihome::ajax();
}
print json_encode($out);
?>