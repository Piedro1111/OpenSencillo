<?php
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
	
	public $ExtHDD;
	public $CondensationSTS;
	public $CondensationLVL;
	public $err;
	public $CPUtemperature;
	public $playerCPUtemperature;
	public $pcstatus;
	public $pcstatusjson;
	
	
	final public function __construct()
	{
		error_reporting(E_ERROR | E_PARSE);
		session_start();
		
		$_SESSION['count']++;
		
		$this->protocol = 'http'; 
		$this->url = 'pihome';
		$this->urlprefix = $this->url;
		$this->port = ':'.$_SERVER['SERVER_PORT'];
		$this->template = '/fw_templates/additional/rpi/production';
		$this->css = $this->urlprefix.$this->template;
		$this->js = $this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/fw_templates/additional/rpi/production';
		$this->stn = $this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/shutdown';
		
		$this->seo = new headerSeo;
		$this->linkmngr = new url;
		$this->logman = new logMan;
		
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
			
			$this->linkmngr->addUrl('','dashboard_pi_page.html.php');
			$this->linkmngr->addUrl('exthdd','exthdd_pi_page.html.php');
			$this->linkmngr->addUrl('gpio','gpio_pi_page.html.php');
			$this->linkmngr->addUrl('shutdown','_');
			$this->linkmngr->addUrl('phpinfo','_');
			//$this->linkmngr->addUrl('logout','_');
		}
		else
		{
			$this->linkmngr->addUrl('','login_page.html.php');
			$this->linkmngr->addUrl('exthdd','login_page.html.php');
			$this->linkmngr->addUrl('gpio','login_page.html.php');
			$this->linkmngr->addUrl('shutdown','login_page.html.php');
		}
		$this->render();
	}
	final private function getExtHDDstatus()
	{
		//ExtHDD status parser
		try
		{
			$this->ExtHDD = file_get_contents('./switchexthdd', true);
			$this->ExtHDD = json_decode($this->ExtHDD,true);
			$this->ExtHDDcontent = fopen ("http://".$this->ExtHDD['ip'], "r");
			if (!$this->ExtHDDcontent) {
				exit;
			}
			$this->ExtHDDcontent = stream_get_contents($this->ExtHDDcontent);
		}
		catch(Exception $e)
		{
			$this->err=$e->getMessage();
		}
		
		return $this->ExtHDD;
	}
	final private function getCondensation()
	{
		//condensation level parser
		try
		{
			$Condensation = file_get_contents('./watercondensator', true);
			$Condensation = json_decode($Condensation,true);
			$this->CondensationSTS = $Condensation['msg'];
			$this->CondensationLVL = $Condensation['water'];
		}
		catch(Exception $e)
		{
			$this->err=$e->getMessage();
			$this->CondensationSTS = 'ERROR';
		}
		
		return $Condensation;
	}
	
	final private function getTemperatures()
	{
		//CPU temperature
		$this->playerCPUtemperature = file_get_contents('./piplayertemperature', true);
		
		$this->CPUtemperature = file_get_contents('./temperature', true);
		$this->CPUtemperature = explode('=',$this->CPUtemperature);
		$this->CPUtemperature = substr($this->CPUtemperature[1], 0, -3);
		try
		{
			$this->playerCPUtemperature=json_decode($this->playerCPUtemperature,true);
			
			$pcjson = file_get_contents('./maincomputer', true);
			$this->pcstatusjson=json_decode($pcjson,true);
			if($this->pcstatusjson['status']==200)
			{
				$this->pcstatus=true;
			}
			else
			{
				$this->pcstatus=false;
			}
		}
		catch(Exception $e)
		{
			$e->getMessage();
			$this->pcstatus=false;
		}
	}
	
	final private function render()
	{
		$logman = $this->logman;
		require_once('.'.$this->template.'/menu_block.html.php');
		if(file_exists('.'.$this->template.'/'.$this->linkmngr->getPage(PAGE)))
		{
			require_once('.'.$this->template.'/'.$this->linkmngr->getPage(PAGE));
		}
		else
		{
			echo "Err 404";
		}
		
		unset($this->CondensationSTS);
		unset($this->CondensationLVL);
		unset($this->err);
		unset($this->CPUtemperature);
		unset($this->playerCPUtemperature);
		unset($this->pcstatus);
		unset($this->pcstatusjson);
	}
	
	final public function ajax()
	{
		require_once('./fw_modules/pihome/ajax_pihome.php');
	}
}
?>