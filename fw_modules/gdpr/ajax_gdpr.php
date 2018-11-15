<?php
$logman=new logMan;
$email=new mailGen;
$emailhead=new headerSeo;

$mysql = new mysqlInterface;
$mysql->config();
$mysql->connect();

$log=$logman->getSignedUser();
$status=array(
	'called'=>$_POST['atype'],
	'date'=>date('Y-m-d'),
	'time'=>date('H:i:s')
);

if($_POST['atype']!='')
{
	$ajax=$_POST;
}else if($_GET['atype']!=''){
	$ajax=$_GET;
	$status=array(
		'called'=>$_GET['atype'],
		'date'=>date('Y-m-d'),
		'time'=>date('H:i:s'),
		'ip'=>$_SERVER['REMOTE_ADDR']
	);
}

switch($ajax['atype'])
{
	default:
}
unset($status['user']);
?>