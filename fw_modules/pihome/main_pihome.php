<?php
$ExtHDD = null;
$CondensationSTS = null;
$CondensationLVL = null;
$err = null;
$CPUtemperature = null;
$playerCPUtemperature = null;
$pcstatus = null;
$pcstatusjson = null;

class pihome
{
	private $url;
	private $urlprefix;
	private $port;
	private $css;
	private $js;
	private $stn;
	private $protocol;
	private $seo;
	private $linkmngr;
	private $logman;
	private $template;
	
	final public function __construct()
	{
		error_reporting(E_ERROR | E_PARSE);
		session_start();
		echo "ok";
		$_SESSION['count']++;
		
		$this->protocol = 'http'; 
		$this->url = 'pihome';
		$this->urlprefix = $this->url;
		$this->port = ':'.$_SERVER['SERVER_PORT'];
		$this->template = '/fw_templates/additional/rpi/production';
		$this->css = $this->urlprefix.$this->template;
		$this->js = $this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/fw_templates/additional/rpi/production';
		$this->stn = $this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/shutdown';
		
		$tihs->seo = new headerSeo;
		$tihs->linkmngr = new url;
		$tihs->logman = new logMan;
		
		//main
		$this->seoGenerator();
		$this->defaultHead(PAGE);
		$this->mainLogic();
	}
	
	final private function seoGenerator()
	{
		$this->seo->encode();
		$this->seo->title($core->coreSencillo->info["FWK"]." - {$this->url}");
		$this->seo->owner("Peter Horváth, phorvath.com");
		$this->seo->custom("<script>var server_name='{$this->protocol}://{$_SERVER['SERVER_NAME']}{$this->port}/{$this->url}';</script>");
		$this->seo->custom("<meta http-equiv='X-UA-Compatible' content='IE=edge'>");
		$this->seo->custom("<meta http-equiv='cache-control' content='no-cache'>");
		$this->seo->custom("<meta http-equiv='expires' content='-1'>");
		$this->seo->custom("<meta http-equiv='pragma' content='no-cache'>");
		$this->seo->bootstrapDefs();
		$this->seo->style("{$this->protocol}://{$_SERVER['SERVER_NAME']}{$this->port}/{$this->css}/fonts/css/font-awesome.min.css");
		$this->seo->style("{$this->protocol}://{$_SERVER['SERVER_NAME']}{$this->port}/{$this->css}/css/animate.min.css");
		$this->seo->style("{$this->protocol}://{$_SERVER['SERVER_NAME']}{$this->port}/{$this->css}/css/custom.css");
		$this->seo->style("{$this->protocol}://{$_SERVER['SERVER_NAME']}{$this->port}/{$this->css}/css/icheck/flat/green.css");
		$this->seo->custom('
		<!--[if lt IE 9]>
			<script src="../assets/js/ie8-responsive-file-warning.js"></script>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		');
		$this->seo->script("{$this->js}/js/extend_js/ext.js");
	}
	
	final private function permDecode()
	{
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
			default:
				$USERtype = 'unknown';
		}
		return $USERtype;
	}
	
	final private function defaultHead($PAGE)
	{
		switch($PAGE)
		{
			case 'phpinfo':
				echo phpinfo();
				die;
			break;
			case 'logout':
				$this->logman->destroySession();
				header('Location: '.$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/');
			break;
			default:
				echo $this->seo->save();
		}
	}
	
	final private function mainLogic()
	{
		if($this->logman->checkSession())
		{
			$this->getExtHDDstatus();
			$this->getCondensation();
			$this->getTemperatures();
			
			$this->url->addUrl('','dashboard_pi_page.html.php');
			$this->url->addUrl('gpio','gpio_pi_page.html.php');
			$this->url->addUrl('shutdown','_');
			$this->url->addUrl('phpinfo','_');
			//$this->url->addUrl('logout','_');
		}
		else
		{
			$url->addUrl('','login_page.html.php');
			$url->addUrl('exthdd','login_page.html.php');
			$url->addUrl('gpio','login_page.html.php');
			$url->addUrl('shutdown','login_page.html.php');
		}
	}
	final private function getExtHDDstatus()
	{
		//ExtHDD status parser
		try
		{
			$ExtHDD = file_get_contents('./switchexthdd', true);
			$ExtHDD = json_decode($ExtHDD,true);
			$ExtHDDcontent = fopen ("http://".$ExtHDD['ip'], "r");
			if (!$ExtHDDcontent) {
				exit;
			}
			$ExtHDDcontent = stream_get_contents($ExtHDDcontent);
		}
		catch(Exception $e)
		{
			$err=$e->getMessage();
		}
		
		return $ExtHDD;
	}
	final private function getCondensation()
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
		
		return $Condensation;
	}
	
	final private function getTemperatures()
	{
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
	}
	
	final private function __destruct()
	{
		require_once('.'.$this->template.'/menu_block.html.php');
		if(file_exists('.'.$this->template.'/'.$this->url->getPage(PAGE)))
		{
			require_once('.'.$this->template.'/'.$this->url->getPage(PAGE));
		}
		
		unset($CondensationSTS);
		unset($CondensationLVL);
		unset($err);
		unset($CPUtemperature);
		unset($playerCPUtemperature);
		unset($pcstatus);
		unset($pcstatusjson);
	}
}
?>