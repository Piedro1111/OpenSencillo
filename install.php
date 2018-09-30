<?php
/*~ install.php
.---------------------------------------------------------------------------.
|  Software: OpenSencillo Installer                                         |
|   Version: 2018.208                                                       |
|   Contact: info@opensencillo.com                                          |
| ------------------------------------------------------------------------- |
|    Author: Bc. Peter Horváth (original founder)                           |
| Copyright (c) 2015-2018, Bc. Peter Horváth. All Rights Reserved.          |
| ------------------------------------------------------------------------- |
|   License: Distributed under the General Public License (GPL)             |
|            http://www.gnu.org/licenses/gpl-3.0.html                       |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'---------------------------------------------------------------------------'
~*/
class installer
{
	private $ini;
	private $mysqlconfig;
	private $afterBootUp;
	private $PHPversion;
	private $jsonlog;
	private $htaccess;
	private $installsql;
	private $storedsql;
	private $insertsql;
	private $yourcode;
	private $post;
	private $get;
	
	final public function __construct()
	{
		$this->loadIni();
	}
	
	final public function parseIniConfig()
	{
		$this->mysqlconfig = new fileSystem($this->ini['new_file_paths']['mysqlconfig']);
		$this->jsonlog = new fileSystem($this->ini['new_file_paths']['firststart']);
		$this->yourcode = new fileSystem($this->ini['new_file_paths']['yourcode']);
		$this->htaccess = new fileSystem($this->ini['new_file_paths']['htaccess']);
		$this->installsql = new fileSystem($this->ini['install_sql']['sql_files_path'].'db_structure.sql');
		//$this->insertsql = new fileSystem($this->ini['install_sql']['sql_files_path'].'db_inserts.sql');
		
		$this->startUpCore();
	}
	
	final private function createYourcode()
	{
		if(!file_exists($this->ini['new_file_paths']['yourcode']))
		{
			$this->yourcode->write('<?php
	$seo = new headerSeo;
	$seo->encode();
	$seo->title($core->coreSencillo->info["FWK"]." - Example page");
	$seo->owner("'.$_POST['user-new-name'].', '.$_POST['user-new-mail'].'");
	$seo->bootstrapDefs();
	echo $seo->save();

	require_once("./fw_templates/system/welcome.default.screen.php");
?>');
			$file = new fileSystem($ini['new_file_paths']['firststart']);
			$json = json_encode(array(	'time'=>date("H:i:s"),
										'date'=>date("Y-m-d"),
										'email'=>$_POST['user-new-mail'],
										'PHP' =>phpversion(),
										'SYSTEM'=>$afterBootUp[0]->info['FWK'],
										'hash'=>$hash
			));
			$file->write($json);
		}
	}
	
	final private function startUpCore()
	{
		$i=0;
		$this->afterBootUp=array();
		$this->afterBootUp[$i++]=new coreSencillo;
	}
	
	final public function path()
	{
		$url = str_ireplace('/install.php','',(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
		return $url.$this->ini['bootstrap_end_paths']['require'][1];
	}
	
	final public function loadIni()
	{
		$this->ini = parse_ini_file('./fw_headers/install.ini',true);
		return $this->ini;
	}
	
	final public function template()
	{
		return $this->ini['bootstrap_end_paths']['require'][0];
	}
	
	final public function core()
	{
		return $this->ini['bootstrap_paths']['require'];
	}
	
	final public function ajax_core()
	{
		return $this->core();
	}
	
	final public function ajax_main()
	{
		$this->post = $_POST;
		$this->get = $_GET;
		$this->getPHPversion();
		
		//start installation
		$this->chmodConfigurationUnlock();
		$this->createConfig();
		$this->htaccess();
		$this->jsonLog();
		$this->createYourcode();
		$this->chmodConfigurationLock();
		//files completed
		//read SQL configuration
		$this->readSQLfile();
		$this->installDB();
	}
	
	/**
	* Get PHP version and return array with version info
	* @example php 7.1.0 in return array[7,1,0]
	* @return array
	*/
	final private function getPHPversion()
	{
		$this->PHPversion = explode(".",phpversion());
		return $this->PHPversion;
	}
	
	/**
	* Install mysql-config
	*/
	final private function createConfig()
	{
		if(!file_exists($this->ini['new_file_paths']['mysqlconfig']))
		{
			$this->mysqlconfig = new fileSystem($this->ini['new_file_paths']['mysqlconfig']);
			$this->mysqlconfig->write('<?php
/*~ mysql-config.php
.---------------------------------------------------------------------------.
|  Software: '.$this->afterBootUp[0]->info['NME'].' SQL Config                                        |
|   Version: '.$this->afterBootUp[0]->info['VSN'].'                                                       |
|   Contact: mail@phorvath.com                                              |
| ------------------------------------------------------------------------- |
|    Author: Bc. Peter Horváth (original founder)                           |
| Copyright (c) 2011-'.date("Y").', Bc. Peter Horváth. All Rights Reserved.          |
| ------------------------------------------------------------------------- |
|   License: Distributed under the General Public License (GPL)             |
|            http://www.gnu.org/licenses/gpl-3.0.html                       |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
´---------------------------------------------------------------------------´
~*/
//changable settings
class database
{
	const host = "'.(isset($_POST['host'])?$_POST['host']:DB_HOST).'";
	const name = "'.(isset($_POST['name'])?$_POST['name']:DB_NAME).'";
	const user = "'.(isset($_POST['user'])?$_POST['user']:DB_USER).'";
	const pass = "'.(isset($_POST['pass'])?$_POST['pass']:DB_PASS).'";
	const type = "'.(isset($_POST['type'])?$_POST['type']:"sams").'";
	const hash = "'.$hash.'";
	const cache= "'.(isset($_POST['cache'])?$_POST['cache']:"0").'";
}
//depecrated variables
//DB Server
$DBHost = database::host;
//SQL access
$DBUser = database::user;
$DBName = database::name;
$DBPass = database::pass;
//SQL type
$DBType = database::type;
//Hash
define("SENCILLO_CONFIG",database::hash);
//Cache
$QUICKCACHE_ON = database::cache;
?>');
		}
	}
	
	/**
	* Create install json log
	*/
	final private function jsonLog()
	{
		$json = json_encode(array(	'time'=>date("H:i:s"),
									'date'=>date("Y-m-d"),
									'email'=>$_POST['email'],
									'PHP' =>phpversion(),
									'SYSTEM'=>$this->afterBootUp[0]->info['FWK'],
									'hash'=>$hash
		));
		$this->jsonlog->write($json);
	}
	
	/**
	* Create htaccess for OpenSencillo
	*/
	final public function htaccess()
	{
		if(!file_exists($this->ini['new_file_paths']['mysqlconfig']))
		{
			foreach($this->ini['htaccess']['cache'] as $key=>$val)
			{
				$expiresbytype.='ExpiresByType '.$val.' "access plus '.$this->ini['htaccess']['cache_size'].' '.$this->ini['htaccess']['cache_unit'].'"'.PHP_EOL;
			}
			if($this->ini['htaccess']['protocol']=="http")
			{
				$protocolinhtaccess='# HTTPS to HTTP
<IfModule mod_rewrite.c>
	RewriteCond %{SERVER_PORT} ^443$
	RewriteCond %{HTTPS} =on
	RewriteRule ^(.*)$ http://'.$_SERVER['SERVER_NAME'].'/$1 [L,R=301]
</IfModule>';
			}
			else
			{
				$protocolinhtaccess='# HTTP to HTTPS
<IfModule mod_rewrite.c>
	RewriteCond %{SERVER_PORT} !^443$ 
	RewriteCond %{HTTPS}  off 
	RewriteRule ^(.*)$ https://'.$_SERVER['SERVER_NAME'].'/$1 [R=301,L]
</IfModule>';
			}
			$this->htaccess->write('# Create with '.$this->afterBootUp[0]->info['FWK'].'.
# Secure ini files
<Files ~ "\.ini">
	Order allow,deny
	Deny from all
</Files>

# Image cache
<IfModule mod_expires.c>
	ExpiresActive on
	'.$expiresbytype.'
</IfModule>

'.$protocolinhtaccess.'

# Rewrite URLs
<IfModule mod_rewrite.c>
	RewriteEngine on
	RewriteBase /
</IfModule>

# Pretty URLs
<IfModule mod_rewrite.c>
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-l
	RewriteRule ^(.*)$ index.php?p=$1 [L,QSA]
</IfModule>

# '.$_SERVER['SERVER_NAME'].' -> www.'.$_SERVER['SERVER_NAME'].'
<IfModule mod_rewrite.c>
	RewriteCond %{HTTP_HOST} !^'.$_SERVER['SERVER_NAME'].'$ [NC]
	RewriteRule ^(.*)$ http://'.$_SERVER['SERVER_NAME'].'/$1 [L,R=301]
</IfModule>');
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	* Pre-write chmod configuration
	*/
	final private function chmodConfigurationUnlock()
	{
		chmod("./fw_core/", 0700);
		chmod("./fw_cache/", 0700);
		chmod("./fw_headers/", 0777);
		chmod("./fw_modules/", 0700);
		chmod("./fw_libraries/", 0700);
		chmod("./fw_script/", 0700);
		chmod("./", 0777);
	}
	
	/**
	* Post-write chomod configuration
	*/
	final private function chmodConfigurationLock()
	{
		chmod("./fw_core/", 0700);
		chmod("./fw_cache/", 0700);
		chmod("./fw_headers/", 0700);
		chmod("./fw_modules/", 0700);
		chmod("./fw_libraries/", 0700);
		chmod("./fw_script/", 0700);
		chmod("./", 0700);
	}
	
	/**
	* Load SQL data
	*/
	final private function readSQLfile()
	{
		$this->storedsql=$this->installsql->read();
	}
	
	/**
	* Install SQL data
	*/
	final private function installDB()
	{
		$mysql = new mysqlInterface($_POST['dbhost'],$_POST['dbname'],$_POST['dbuser'],$_POST['dbpass']);
		$mysql->config();
		$mysql->connect();
		$mysql->addQuery($this->storedsql);
		$update = array(
			'email'=>$_POST['uemail'],
			'fname'=>$_POST['ufname'],
			'lname'=>$_POST['ulname'],
			'perm'=>1111,
			'ip'=>$_SERVER['REMOTE_ADDR'],
			'agent'=>$_SERVER['HTTP_USER_AGENT'],
			'date'=>date('Y-m-d'),
			'time'=>date('H:i:s')
		);
		$updatespecial['pass'] = "MD5('".(($_POST['upassw']==$_POST['urtpsw'])?$_POST['upassw']:'')."')";
		$this->mysqlinterface->update(array('users'=>array(
			'condition'=>array(
				'`id`=1'
			),
			'set'=>$update,
			'set()'=>$updatespecial
		)));
		$mysql->execute();
	}
}

$init = new installer;
if($_GET['ajax']==true)
{
	$core = $init->ajax_core();
	foreach($core as &$val)
	{
		require_once(__DIR__ . $val);
	}
	$init->parseIniConfig();
	$init->ajax_main();
}
else
{
	$core = $init->core();
	foreach($core as &$val)
	{
		require_once(__DIR__ . $val);
	}
	$init->parseIniConfig();
	require_once(__DIR__ . $init->template());
}
?>
