<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
require_once("./basicstrap.php");

$logman=new logMan;
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
		if(filter_var($ajax[$ajax['atype'].'email'],FILTER_VALIDATE_EMAIL))
		{
			$status['user']=$logman->output("`login`='".strtolower($ajax[$ajax['atype'].'email'])."' AND `pass`=MD5('".$ajax[$ajax['atype'].'pass']."')","`id` ASC",1);
			if($status['user']['line'][1][0]>0)
			{
				$status['status']='authorized';
				$status['code']=202;
				
				$_SESSION['userid']=$status['user']['line'][1][0];
				$_SESSION['login']=$status['user']['line'][1][3];
				$_SESSION['email']=$status['user']['line'][1][5];
				$_SESSION['perm']=$status['user']['line'][1][8];
				$_SESSION['sessionid']=session_id();
				$_SESSION['start']=date('Y-m-d H:i:s');
				if($status['user']['line'][1][1]=='first_use')
				{
					$_SESSION['tutorial']=true;
				}
				else
				{
					$_SESSION['tutorial']=false;
				}
				$logman->update('`id`='.$status['user']['line'][1][0],"`sign`='".$_SESSION['sessionid']."'");
			}
			else
			{
				$status['status'] = 'unauthorized';
				$status['code'] = 404;
			}
		} else {
			$status['status'] = 'invalid';
			$status['code'] = 403;
		}
		break;
	case 'ereg':
		//TODO
		// move to logman addNewUser($pass,$perm)
		$logman->openTable('users');
		if(filter_var($_POST[$ajax['atype'].'email'], FILTER_VALIDATE_EMAIL))
		{
			if($_POST[$ajax['atype'].'pass']===$_POST[$ajax['atype'].'rtp'])
			{
				$status['user']=$logman->output("`login`='".strtolower($ajax[$ajax['atype'].'email'])."'","`id` ASC",1);
				if(empty($status['user']['line'][1][0]))
				{
					try {
						$name = explode(" ",$_POST[$ajax['atype'].'fullname']);
						$logman->insert("'first_use',0,'" . strtolower($_POST[$ajax['atype'].'email']) . "',MD5('" . $_POST[$ajax['atype'].'pass'] . "'),'" . strtolower($_POST[$ajax['atype'].'email']) . "','" . $logman->clean(ucwords(strtolower($name[0]))) . "','" . $logman->clean(ucwords(strtolower($name[1]))) . "',1000,'" . $log['external_ip'] . ":" . $log['port'] . "','" . $log['agent'] . "',DATE(NOW()),TIME(NOW())");
						$status['status'] = 'ok';
						$status['code'] = 200;
					} catch (Exception $e) {
						$status['status'] = 'failed';
						$status['code'] = 417;
					}
				}
				else
				{
					$status['status'] = 'exist';
					$status['code'] = 409;
				}
			}
			else
			{
				$status['status'] = 'conflict pass retype';
				$status['code'] = 409.1;
			}
		} else {
			$status['status'] = 'invalid';
			$status['code'] = 403;
		}
		break;
	case 'forgot':
		//TODO
		// forgot pass
		break;
	default:
		$status['status'] = 'not acceptable';
		$status['code'] = 405;
		break;
}
unset($status['user']);
print json_encode($status);
?>
