<?php
	error_reporting(E_ERROR | E_PARSE);
	session_start();

class adminsystem
{
	private $url;
	private $port;
	private $css;
	private $js;
	private $stn;
	
	public function __construct()
	{
		$this->url = 'pihome';
		$this->port = ':'.$_SERVER['SERVER_PORT'];
		$this->css = $this->url.'/fw_templates/additional/rpi/production';
		$this->js = 'http://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/fw_templates/additional/rpi/production';
		$this->stn = 'pihome';
	}
}
	$_SESSION['count']++;
	
	$PORT = ':'.$_SERVER['SERVER_PORT'];
	$ADMIN_SYSTEM = 'pihome';
	$CSS = $ADMIN_SYSTEM.'/fw_templates/additional/rpi/production';
	$JS  = 'http://'.$_SERVER['SERVER_NAME'].$PORT.'/'.$ADMIN_SYSTEM.'/fw_templates/additional/rpi/production';
	$STN = 'http://'.$_SERVER['SERVER_NAME'].$PORT.'/'.$ADMIN_SYSTEM.'/shutdown';
	
	$seo = new headerSeo;
	$url = new url;
	$logman = new logMan;
	
	$seo->encode();
	$seo->title($core->coreSencillo->info["FWK"]." - RaspberryPi");
	$seo->owner("Peter Horváth, phorvath.com");
	$seo->custom("<script>var server_name='http://{$_SERVER['SERVER_NAME']}{$PORT}/{$ADMIN_SYSTEM}';</script>");
	$seo->custom("<meta http-equiv='X-UA-Compatible' content='IE=edge'>");
	$seo->custom("<meta http-equiv='cache-control' content='no-cache'>");
	$seo->custom("<meta http-equiv='expires' content='-1'>");
	$seo->custom("<meta http-equiv='pragma' content='no-cache'>");
	$seo->bootstrapDefs();
	$seo->style("http://{$_SERVER['SERVER_NAME']}{$PORT}/$CSS/fonts/css/font-awesome.min.css");
	$seo->style("http://{$_SERVER['SERVER_NAME']}{$PORT}/$CSS/css/animate.min.css");
	$seo->style("http://{$_SERVER['SERVER_NAME']}{$PORT}/$CSS/css/custom.css");
	$seo->style("http://{$_SERVER['SERVER_NAME']}{$PORT}/$CSS/css/icheck/flat/green.css");
	$seo->custom('
	<!--[if lt IE 9]>
		<script src="../assets/js/ie8-responsive-file-warning.js"></script>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	');
	$seo->script("{$JS}/js/extend_js/ext.js");
	
	switch($_SESSION['perm'])
	{
		case '1000':
			$USERtype = 'ban';
			break;
		case '1100':
			$USERtype = 'user';
			break;
		case '1110':
			$USERtype = 'vip';
			break;
		case '1111':
			$USERtype = 'admin';
			break;
	}
	switch(PAGE)
	{
		case 'phpinfo':
			echo phpinfo();
			die;
		break;
		case 'logout':
			$logman->destroySession();
			header('Location: http://'.$_SERVER['SERVER_NAME'].$PORT.'/'.{$ADMIN_SYSTEM}.'/');
		break;
		default:
			echo $seo->save();
	}
	if($logman->checkSession())
	{
		//condensation level parser
		try
		{
			$Condensation = file_get_contents('./watercondensator', true);
			$Condensation = json_decode($Condensation,true);
			$CondensationSTS = $Condensation['msg'];
			$CondensationLVL = $Condensation['water'];
		}
		catch(Exception $e)
		{
			$err=$e->getMessage();
			$CondensationSTS = 'ERROR';
		}
		
		//CPU temperature
		$playerCPUtemperature = file_get_contents('./piplayertemperature', true);
		
		$CPUtemperature = file_get_contents('./temperature', true);
		$CPUtemperature = explode('=',$CPUtemperature);
		$CPUtemperature = substr($CPUtemperature[1], 0, -3);
		try
		{
			$playerCPUtemperature=json_decode($playerCPUtemperature,true);
			
			$pcjson = file_get_contents('./maincomputer', true);
			$pcstatusjson=json_decode($pcjson,true);
			if($pcstatusjson['status']==200)
			{
				$pcstatus=true;
			}
			else
			{
				$pcstatus=false;
			}
		}
		catch(Exception $e)
		{
			$e->getMessage();
			$pcstatus=false;
		}
		
		$url->addUrl('','dashboard_pi_page.html.php');
		$url->addUrl('gpio','gpio_pi_page.html.php');
		$url->addUrl('shutdown','_');
		$url->addUrl('phpinfo','_');
		//$url->addUrl('logout','_');
	}
	else
	{
		$url->addUrl('','login_page.html.php');
	}
	require_once('./fw_templates/additional/rpi/production/menu_block.html.php');
	if(file_exists('./fw_templates/additional/rpi/production/'.$url->getPage(PAGE)))
	{
		require_once('./fw_templates/additional/rpi/production/'.$url->getPage(PAGE));
	}
?>