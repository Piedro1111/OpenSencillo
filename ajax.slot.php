<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
require("./basicstrap.php");
require("./fw_libraries/login.management.logman.php");

$logman=new logMan;
/*if($logman->install()===false)
{
	die('sys:complete_failure');
}*/
$log=$logman->getSignedUser();
$status=array(
	'called'=>$_POST['atype'],
    'data'=>date('Y-m-d'),
    'time'=>date('H:i:s')
);
if($_POST['atype']!='')
{
	$ajax=$_POST;
}
switch($ajax['atype'])
{
	case 'login':
	    //TODO
	    // login system
	    $logman->openTable('users');
	    if(filter_var($ajax['email'],FILTER_VALIDATE_EMAIL))
	    {
    	    if($logman->output("`login`='".strtolower($ajax['email'])."' AND `pass`=MD5('".$ajax['pass']."')","`id` ASC",1)!=false)
    	    {
    	        $status['status']='authorized';
    	        $status['code']=202;
    	        $status['user']=$logman->output("`login`='".strtolower($ajax['email'])."' AND `pass`=MD5('".$ajax['pass']."')","`id` ASC",1);

    	        $_SESSION['userid']=$status['user']['line'][1]['id'];
    	        $_SESSION['login']=$status['user']['line'][1]['login'];
    	        $_SESSION['email']=$status['user']['line'][1]['email'];
    	        $_SESSION['perm']=$status['user']['line'][1]['perm'];
    	        $_SESSION['sessionid']=session_id();
    	        $_SESSION['start']=date('Y-m-d H:i:s');
    	        if($status['user']['line'][1]['sign']=='first_use')
    	        {
    	            $_SESSION['tutorial']=true;
    	        }
    	        else
    	        {
    	            $_SESSION['tutorial']=false;
    	        }
    	        $logman->update('`id`='.$status['user']['line'][1]['id'],"`sign`='".$_SESSION['sessionid']."'");

    	        unset($status['user']['line']);
    	    }
    	    else
    	    {
    	    	$status['status']='unauthorized';
    	    	$status['code']=404;
    	    }
	    }
	    else
	    {
	        $status['status']='invalid';
	        $status['code']=403;
	    }
	break;
	case 'ereg':
	    //TODO
	    // move to logman addNewUser($pass,$perm)
	    $logman->openTable('users');
	    if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
	    {
    	    if($logman->output("`login`='".$_POST['email']."'","`id` ASC",1)==false)
    	    {
    	        try
    	        {
    	            $logman->insert("'first_use',0,'".strtolower($_POST['email'])."',MD5('".$_POST['pass']."'),'".strtolower($_POST['email'])."','".$logman->clean(ucwords(strtolower($_POST['fname'])))."','".$logman->clean(ucwords(strtolower($_POST['lname'])))."',1000,'".$log['external_ip'].":".$log['port']."','".$log['agent']."',DATE(NOW()),TIME(NOW())");
    	            $status['status']='ok';
    	            $status['code']=200;
    	        }
    	        catch(Exception $e)
    	        {
    	        	$status['status']='failed';
    	        	$status['code']=417;
    	        }
    	    }
    	    else
    	    {
    	    	$status['status']='exist';
    	    	$status['code']=409;
    	    }
	    }
	    else
	    {
	        $status['status']='invalid';
	        $status['code']=403;
	    }
	break;
	case 'fgot':
	   //TODO
	   // forgot pass
	break;
}
print json_encode($status);
?>
