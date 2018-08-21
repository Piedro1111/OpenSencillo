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
}else if(($_GET['atype']=='piplayer')||($_GET['atype']=='waterCondensator')||($_GET['atype']=='switchExtHdd')||($_GET['atype']=='iftttweather')){
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
		$mysql->insert(array(
			'sensors'=>array(
				'id'=>'',
				'sensor'=>$ajax['atype'],
				'data'=>json_encode($status),
				'date'=>$status['date'],
				'time'=>$status['time'],
			)
		));
		$mysql->execute();
	break;
	case 'waterCondensator':
		$status['status'] = 'ok';
		$status['code'] = 200;
		$status['water'] = ($ajax['status']==1?0:1);
		$status['msg'] = ($ajax['status']==1?'OK':'FULL');
		$fsys=new fileSystem('watercondensator');
		$fsys->write(json_encode($status));
		$mysql->insert(array(
			'sensors'=>array(
				'id'=>'',
				'sensor'=>$ajax['atype'],
				'data'=>json_encode($status),
				'date'=>$status['date'],
				'time'=>$status['time'],
			)
		));
		$mysql->execute();
	break;
	case 'switchExtHdd::action':
		$ExtHDD = file_get_contents('./switchexthdd', true);
		$ExtHDD = json_decode($ExtHDD,true);
		if(($_SESSION['perm']>=1110)&&($logman->checkSession()))
		{
			ignore_user_abort(true);
			if($ajax['action']==1)
			{
				$file = fopen ("http://".$ExtHDD['ip']."/zapnut", "r");
				if (!$file) {
					exit;
				}
				$status['code'] = 200;
				$status['status'] = 'on::'.$ExtHDD['ip'];
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
			$mysql->insert(array(
				'sensors'=>array(
					'id'=>'',
					'sensor'=>$ajax['atype'],
					'data'=>json_encode($status),
					'date'=>$status['date'],
					'time'=>$status['time'],
				)
			));
			$mysql->execute();
		}
	break;
	case 'switchExtHdd':
		$status['code'] = 200;
		$fsys=new fileSystem('switchexthdd');
		$fsys->write(json_encode($status));
		$mysql->insert(array(
			'sensors'=>array(
				'id'=>'',
				'sensor'=>$ajax['atype'],
				'data'=>json_encode($status),
				'date'=>$status['date'],
				'time'=>$status['time'],
			)
		));
		$mysql->execute();
	break;
	case 'iftttweather':
		$mysql->insert(array(
			'sensors'=>array(
				'id'=>'',
				'sensor'=>$ajax['atype'],
				'data'=>json_encode($_REQUEST),
				'date'=>$status['date'],
				'time'=>$status['time'],
			)
		));
		$mysql->execute();
		$status['code'] = 200;
		$status['status'] = 'ok';
	break;
}
unset($status['user']);
?>