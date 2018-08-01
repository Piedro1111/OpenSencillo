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
}

switch($ajax['atype'])
{
	case 'login':
		$status = $logman->login($ajax);
		//var_dump($logman->testout());
		break;
	case 'ereg':
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
		$_POST['email']=$_POST[$ajax['atype'].'email'];
		$status=$logman->forgot();
		if($status['code']===200)
		{
			$log=$logman->getSignedUser();
			$logman->openTable('users');
			$logman->update("`email`='{$_POST['email']}'","`sign`='change_pass',`pass`=MD5('{$status['confirm-code']}'),`ip`='".$log['external_ip'].":".$log['port']."',`agent`='".$log['agent']."',`date`='".$status['date']."',`time`='".$status['time']."'");
			$email->to($_POST['email']);
			$email->from('info@'.$_SERVER['SERVER_NAME']);
			$email->subject('New password - '.$_SERVER['SERVER_NAME']);
			$email->html();
			$emailhead->encode();
			$email->body($emailhead->save()."<body><p>Hello {$_POST['email']},</p><p>your new password is <b>{$status['confirm-code']}</b>.</p></body></html>");
			$email->send();
		}
		break;
	case 'removeUser::action':
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$mysql->delete(array('users'=>array(
				'condition'=>array(
					'`id`='.$ajax['user'],
					'`perm`<1111'
				)
			)));
			$mysql->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'killSession::action':
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$mysql->update(array('users'=>array(
				'condition'=>array(
					'`id`='.$ajax['user'],
					'`perm`<1111'
				),
				'set'=>array(
					'sign'=>'kicked'
				)
			)));
			$mysql->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'banUser::action':
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$mysql->update(array('users'=>array(
				'condition'=>array(
					'`id`='.$ajax['user'],
					'`perm`<1111'
				),
				'set'=>array(
					'sign'=>'banned',
					'active'=>-1,
					'perm'=>0,
					
				)
			)));
			$mysql->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'library::changestatus':
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$mysql->update(array('virtual_system_config'=>array(
				'condition'=>array(
					'`id`='.$ajax['lib'],
					'`perm`>=0'
				),
				'set'=>array(
					'switch'=>$ajax['libstatus']
				)
			)));
			$mysql->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'module::changestatus':
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$mysql->update(array('virtual_system_config'=>array(
				'condition'=>array(
					'`id`='.$ajax['mod'],
					'`perm`>=0'
				),
				'set'=>array(
					'switch'=>$ajax['modstatus']
				)
			)));
			$mysql->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	default:
		$status['status'] = 'not acceptable';
		$status['code'] = 405;
		break;
}
//unset($status['user']);
print json_encode($status);
?>