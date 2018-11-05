<?php
/**
 * Modules model
 */
class construct
{
	public $url;
	public $urlprefix;
	public $port;
	public $css;
	public $js;
	public $stn;
	public $protocol;
	public $seo;
	public $linkmngr;
	public $logman;
	public $template;
	public $usertype;
	public $mainmenu;
	public $libtype;
	public $alltemplates;
	public $mysqlinterface;
	public $page;
	public $server_url;
	public $server_clean;
	public $email;
	public $emailhead;
	public $fullpath;
	public $fullpathwithoutget;
	
	protected $mediapath;
	protected $video;
	protected $music;
	protected $images;
	
	protected $defaultcfg;
	
	private $pageInURL;
	
	final public function __construct($page=false)
	{
		error_reporting(E_ERROR | E_PARSE);
		//session_start();
		
		$this->mediapath = './fw_media/';
		
		if($page)
		{
			$this->setPage($page);
		}
		
		$this->email = new mailGen;
		$this->emailhead = new headerSeo;
		
		$_SESSION['count']++;
		
		if($this->getPage()!='ajax.slot.php')
		{
			$this->seo = new headerSeo;
			$this->linkmngr = new url;
		}
		$this->logman = new logMan;
		$this->mysqlinterface = new mysqlinterface;

		$this->mysqlinterface->config();
		$this->mysqlinterface->connect();
		
		//main
		if($this->getPage()!='ajax.slot.php')
		{
			$this->mainLogic();
		}
	}
	
	/**
	* Install basic configuration for mods
	* @param mod modname string
	* @param protocol 'http' or 'https' string
	* @param template path string
	*/
	final public function install($mod,$protocol,$template)
	{
		$mysql->mysqlinterface->insert(array(
			'virtual_system_config'=>array(
				'id'=>'',
				'module'=>$mod,
				'perm'=>0,
				'switch'=>1,
				'function'=>'mod:'.$mod,
				'command'=>$mod,
				'commander'=>0
			)
		));
		$mysql->mysqlinterface->insert(array(
			'virtual_system_config'=>array(
				'id'=>'',
				'module'=>$mod,
				'perm'=>0,
				'switch'=>1,
				'function'=>'cfg:'.$protocol.','.$mod.','.$template,
				'command'=>$mod,
				'commander'=>0
			)
		));
		$mysql->mysqlinterface->execute();
	}
	
	/**
	 * Set and get pages
	 */
	final public function setPage($page)
	{
		$this->pageInURL = $page;
	}
	final public function getPage()
	{
		return $this->pageInURL;
	}
	
	/**
	 * Media list
	 */
	final public function setupGallery()
	{
		$this->video = scandir($this->mediapath.'media_videos/');
		$this->images = scandir($this->mediapath.'media_imgs/');
		$this->music = scandir($this->mediapath.'media_sounds/');
	}
	
	/**
	 * Read config data
	 * 
	 * @return array
	 */
	final public function readVSC($mod)
	{
		$this->mysqlinterface->select(array(
			'virtual_system_config'=>array(
				'condition'=>array(
					'`module`="'.$mod.'"',
					'`function` LIKE "cfg:%"',
					'`command` LIKE "config_mod:%"',
					'`switch`=1'
				)
			)
		));
		$cfg = $this->mysqlinterface->execute();
		$cfg = explode(':',$cfg[0]['command']);
		$cfg = explode(',',$cfg[1]);
		
		return $cfg;
	}
	
	/**
	 * Configure mod
	*/
	final public function config_mod($protocol,$url,$template)
	{
		$this->protocol = $protocol; //'http'
		$this->url = $url; //'pihome'
		$this->urlprefix = $this->url;
		$this->port = ':'.$_SERVER['SERVER_PORT'];
		$this->template = $template; //'/fw_templates/additional/rpi/production'
		$this->css = $this->urlprefix.$this->template;
		$this->js = $this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.(($this->url!='')?'/'.$this->url:$this->url).$this->template;
		$this->server_url = $this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.(($this->url!='')?'/'.$this->url:$this->url);
		$this->server_clean = $this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port;
		$this->fullpath = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER[HTTP_HOST]}{$_SERVER[REQUEST_URI]}";
		$this->fullpathwithoutget = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER[HTTP_HOST]}".strtok($_SERVER["REQUEST_URI"],'?');
		$this->stn = $this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.(($this->url!='')?'/'.$this->url:$this->url).'/shutdown';
	}
	
	/**
	 * Generate edit url universal
	 * 
	 * @return array
	 */
	final public function urlEdit($key,$template)
	{
		$this->mysqlinterface->select(array(
			'menu'=>array(
				'condition'=>array('`template_file`="'.$template.'"')
			)
		));
		$out = $this->mysqlinterface->execute();
		return $out[0][$key];
	}
	
	/**
	 * permDecode - decode basic permission if parameter perm is integer.
	 * 
	 * @param int $perm example $this->permDecode(1100)
	 * 
	 * @return string
	 */
	final protected function permDecode($perm=false)
	{
		if($perm===false)
		{
			$perm=$_SESSION['perm'];
		}
		$this->usertype = 'unknown';
		
		$this->mysqlinterface->select(array(
			'perm_list'=>array(
				'condition'=>array(
					"`perm`='{$perm}'"
				)
			)
		));
		$permdata = $this->mysqlinterface->execute();
		$this->usertype=$permdata[0]['usertype'];
		
		return $this->usertype;
	}
	
	/**
	 * Get full perm list
	 */
	final protected function permList()
	{
		$this->mysqlinterface->select(array(
			'perm_list'=>array(
				'condition'=>array(
					"`id`>0"
				),
				'sort'=>array(
					'asc'=>'perm'
				)
			)
		));
		$permlist = $this->mysqlinterface->execute();
		return $permlist;
	}
}

/**
 * Create connection to library
 * @name modules and library loader
 * @version 2017.104
 * @category Sencillo Library
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/licenses/gpl-3.0.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class library
{
	protected $all_data_sencillo;
	private $mysql;
	protected $files;
	protected $modules;
	
	private $config = array();
	
	public $lib;
	
	public function __construct()
	{
		$this->config('lib_folder','fw_libraries');
		$this->config('mod_folder','fw_modules');
		$this->config('lib_ignore','lib_identificator.php');
	}

	/**
	 * Create complet data structure
	 */
	private function createStructure()
	{
		$this->lib=array("id"=>array(),
						 "function"=>array(),
						 "boxid"=>array(),
						 "status"=>array());
		$this->all_data_sencillo=array("left"=>array(),
									   "center"=>array(),
									   "right"=>array(),
									   "foot"=>array(),
									   "admin"=>array());
	}
	
	/**
	 * Create lib / mod list
	 */
	private function setupSencillo()
	{
		$this->files = scandir('./' . $this->config['lib_folder'] . '/');
		
		if(file_exists('./' . $this->config['mod_folder'] . '/'))
		{
			$this->modules = scandir('./' . $this->config['mod_folder'] . '/');
		}
	}
	
	private function loaderSencillo()
	{
		$this->mysql = new mysqlInterface();
		$this->mysql->config();
		$this->mysql->connect();
		$this->mysql->select(array(
			'virtual_system_config'=>array(
				'condition'=>array(
					'`id`>0',
					'`module`!="#none"',
					'`perm`>=0',
					'`switch`=1',
					'`function` LIKE "lib:%"'
				),
				'sort'=>array(
					'asc'=>'sort'
				)
			)
		));
		$data = $this->mysql->execute();
		
		foreach($data as $val)
		{
			$this->files[] = $val['module'].'.php';
		}
		
		$this->mysql->select(array(
			'virtual_system_config'=>array(
				'condition'=>array(
					'`id`>0',
					'`module`!="#none"',
					'`perm`>=0',
					'`switch`=1',
					'`function` LIKE "mod:%"'
				),
				'sort'=>array(
					'asc'=>'sort'
				)
			)
		));
		$data = $this->mysql->execute();
		
		foreach($data as $val)
		{
			$this->modules[] = $val['module'];
		}
		
		//var_dump($this->modules);
		//die;
	}
	
	
	/**
	 * Open libraries
	 */
	private function openFiles()
	{
		foreach($this->files as $value)
		{
			$test=(($value!='.')&&($value!='..')&&($value!=$this->config['lib_ignore'])&&($value!='examples')?true:false);
			
			if(($value!='.')&&($value!='..')&&($value!=$this->config['lib_ignore'])&&($value!='examples'))
			{
				$this->lib['id'][]=$value;
			}
		}

		foreach($this->lib['id'] as $value)
		{
			try
			{
				//require("./fw_libraries/".$value);
				$NAME=explode(".",$value);
				$MOD_DESC=$NAME[0].','.$NAME[1];
				$this->lib['name'][]=$NAME[2];
				$this->lib['function'][]=$MOD_DESC;
				$this->lib['version'][]=$VERSION;
				$this->lib['status'][]='OK:'.$value;
				$this->lib['path'][]='./' . $this->config['lib_folder'] . '/'.$value;
				$this->lib['install'][]='../' . $this->config['lib_folder'] . '/'.$value;
			}
			catch(Exception $e)
			{
				$this->lib['status']=array('ERROR:'.$value.':'.$e);
			}
		}
	}
	
	/**
	 * Open modules
	 */
	private function openModules()
	{
		foreach($this->modules as $value)
		{
			$test=((file_exists('./' . $this->config['mod_folder'] . '/'.$value.'/'))&&($value!='.')&&($value!='..')&&($value!=$this->config['lib_ignore'])&&($value!='examples')?true:false);
	
			if((file_exists('./' . $this->config['mod_folder'] . '/'.$value.'/'))&&($value!='.')&&($value!='..')&&($value!=$this->config['lib_ignore'])&&($value!='examples'))
			{
				$this->lib['id'][]=$value;
			}
		}
	
		foreach($this->lib['id'] as $value)
		{
			try
			{
				$this->lib['name'][]=$value;
				$this->lib['function'][]='custom_module';
				$this->lib['status'][]='OK:'.$value;
				$this->lib['path'][]='./' . $this->config['mod_folder'] . '/'.$value.'/info_'.$value.'.php';//information about module
				$this->lib['path'][]='./' . $this->config['mod_folder'] . '/'.$value.'/update_'.$value.'.php';//update database for module
				$this->lib['path'][]='./' . $this->config['mod_folder'] . '/'.$value.'/install_'.$value.'.php';//installer
				$this->lib['path'][]='./' . $this->config['mod_folder'] . '/'.$value.'/main_'.$value.'.php';//main module
				$this->lib['path'][]='./' . $this->config['mod_folder'] . '/'.$value.'/'.$value.'.php';//basic module
			}
			catch(Exception $e)
			{
				$this->lib['status'][]='ERROR:'.$value.':'.$e;
			}
		}
	}
	
	/**
	 * Install main mod_id. Safe start only.
	 * @param array $ignored
	 */
	public function install($ignored)
	{
		$this->createStructure();
		$this->setupSencillo();
		$this->files = array_diff(scandir('../' . $this->config['lib_folder'] . '/'),$ignored);
		$this->openFiles();
	}
	
	/*
	 * Set one value and his key in config
	 * @param string $key
	 * @param string $val
	 * @return bool
	 */
	public function config($key,$val)
	{
		unset($this->config[$key]);
		if(empty($this->config[$key]))
		{
			$this->config[$key] = $val;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Start main mod_id proces
	 */
	public function start()
	{
		$this->createStructure();
		$this->loaderSencillo();
		$this->openFiles();
		$this->openModules();
	}
	
	/**
	 * Export data obtained after main proces
	 * @return mixed [(left/right/center/foot/admin)] || [(numeric value)]
	 */
	public function export()
	{
		return $this->all_data_sencillo;
	}
	
	/**
	 * Export informations about libraries
	 * @return array
	 */
	public function status()
	{
		return $this->lib;
	}
	
	/**
	 * Return array with unique path for class loader
	 * @return arrray
	 */
	public function exportPath()
	{
		return array_unique($this->lib['path']);
	}
	
	/**
	 * Return array with unique installer path for installer class loader
	 * @return arrray
	 */
	public function installerPath()
	{
		return array_unique($this->lib['install']);
	}
}
?>
