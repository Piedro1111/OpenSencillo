<?php
$logman=new logMan;
$email=new mailGen;
$emailhead=new headerSeo;

$log=$logman->getSignedUser();
$status=array(
	'called'=>$_POST['atype'],
	'date'=>date('Y-m-d'),
	'time'=>date('H:i:s')
);
if($_POST['atype']!='')
{
	$ajax=$_POST;
}else if(($_GET['atype']=='piplayer')||($_GET['atype']=='waterCondensator')||($_GET['atype']=='switchExtHdd')){
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
	case 'install::first_info':
		break;
	case 'install::automatic_validation':
		break;
	case 'install::create_fs':
		break;
	case 'install::create_db':
		break;
	case 'raspberrypi::shutdown':
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			exec( 'sudo -u www-data -S shutdown -h '.$ajax['time'], $output, $return_val );
			//print_r( $output );
			$status['status'] = 'shutdown';
			$status['code'] = $return_val;
		}
		else
		{
			$status['status'] = 'access denied';
			$status['code'] = 403;
		}
		break;
	case 'raspberrypi::restart':
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			exec( 'sudo -u www-data -S shutdown -r '.$ajax['time'], $output, $return_val );
			//print_r( $output );
			$status['status'] = 'restart';
			$status['code'] = $return_val;
		}
		else
		{
			$status['status'] = 'access denied';
			$status['code'] = 403;
		}
		break;
	case 'raspberrypi::status':
		$status['status'] = 'ok';
		$status['code'] = 200;
		break;
	case 'raspberrypi::gpio::out::set':
		if(($_SESSION['perm']>=1110)&&($logman->checkSession()))
		{
			shell_exec("sudo -u www-data -S cd ../../");
			shell_exec("sudo -u www-data -S /usr/local/bin/gpio -g mode {$ajax['gpio']} out");
			$status['status'] = 'ok';
			$status['code'] = 200;
		}
		else
		{
			$status['status'] = 'access denied';
			$status['code'] = 403;
		}
		break;
	case 'raspberrypi::gpio::val::set':
		if(($_SESSION['perm']>=1110)&&($logman->checkSession()))
		{
			shell_exec("sudo -u www-data -S cd ../../");
			shell_exec("sudo -u www-data -S /usr/local/bin/gpio -g write {$ajax['gpio']} {$ajax['set']}");
			$status['status'] = 'ok';
			$status['code'] = 200;
		}
		else
		{
			$status['status'] = 'access denied';
			$status['code'] = 403;
		}
		break;
	case 'raspberrypi::gpio::reset::all':
		if($logman->checkSession())
		{
			if($_SESSION['perm']>=1110)
			{
				shell_exec("sudo -u www-data -S cd ../../");
				$i=0;
				while($i<=30)
				{
					shell_exec("sudo -u www-data -S /usr/local/bin/gpio -g mode $i out");
					shell_exec("sudo -u www-data -S /usr/local/bin/gpio -g write $i 0");
					$i++;
				}
				
				$status['status'] = 'ok';
				$status['code'] = 200;
			}
			else
			{
				$status['status'] = 'access denied';
				$status['code'] = 403;
			}
		}
		break;
	case 'piplayer':
		$status['status'] = 'ok';
		$status['code'] = 200;
		$status['temp'] = $ajax['temp'];
		$fsys=new fileSystem('piplayertemperature');
		$fsys->write(json_encode($status));
	break;
	case 'waterCondensator':
		$status['status'] = 'ok';
		$status['code'] = 200;
		$status['water'] = ($ajax['status']==1?0:1);
		$status['msg'] = ($ajax['status']==1?'OK':'FULL');
		$fsys=new fileSystem('watercondensator');
		$fsys->write(json_encode($status));
	break;
	case 'switchExtHdd::action':
		$ExtHDD = file_get_contents('./switchexthdd', true);
		$ExtHDD = json_decode($ExtHDD,true);
		if(($_SESSION['perm']>=1110)&&($logman->checkSession()))
		{
			if($ajax['action']==1)
			{
				$file = fopen ("http://".$ExtHDD['ip']."/zapnut", "r");
				if (!$file) {
					exit;
				}
				$status['code'] = 200;
				$status['status'] = 'on::'.$ExtHDD['ip'];
				sleep(10);
			}
			if($ajax['action']==0)
			{
				sleep(10);
				$file = fopen ("http://".$ExtHDD['ip']."/vypnut", "r");
				if (!$file) {
					exit;
				}
				$status['code'] = 200;
				$status['status'] = 'off::'.$ExtHDD['ip'];
			}
		}
	break;
	case 'switchExtHdd':
		$status['code'] = 200;
		$fsys=new fileSystem('switchexthdd');
		$fsys->write(json_encode($status));
	break;
	case 'removeUser::action':
		$status['code'] = 200;
		$status['status'] = 'ok';
		$mysql = new mysqlInterface;
		$mysql->config();
		$mysql->connect();
		$mysql->delete(array('users'=>array(
			'condition'=>array(
				'`id`='.$ajax['user'],
				'`perm`<1111'
			)
		)));
		$status['debug'] = $mysql->execute();
	break;
	default:
		$status['status'] = 'not acceptable';
		$status['code'] = 405;
		break;
}
//unset($status['user']);
print json_encode($status);
?>