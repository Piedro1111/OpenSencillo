<?php
/**
 * Core installer
 * @name OpenSencillo SQL Installer
 * @version 2015.109
 * @category core
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
error_reporting(E_ERROR | E_PARSE);
require_once("./core_interface.php");
require_once("./core_functions.php");
require_once("../fw_libraries/lib_identificator.php");

$inc=new library;
$inc->start();
$paths = $inc->installerPath();
$realPath = array();
foreach($paths as $val)
{
	if(file_exists($val))
	{
		require_once("$val");
		$realPath[] = $val;
	}
}
unset($paths);

$i=0;
$afterBootUp=array();
$afterBootUp[$i++]=new coreSencillo;
$seo=new headerSeo;
$seo->encode();
$seo->title($afterBootUp[0]->info['FWK'].' - Installer');
$seo->owner('Bc. Peter Horváth');
$seo->bootstrapDefs();
$seo->save();
echo $seo->seo;
$PHPversion=explode(".",phpversion());
if(($_GET['install']=='true')&&($PHPversion[0]>=5))
{
	chmod("../fw_headers/", 0777);
	if((($_POST['host']!="")&&($_POST['user']!="")&&($_POST['name']!="")&&($_POST['pass']!=""))||((defined('DB_USER'))&&(defined('DB_NAME'))&&(defined('DB_PASS'))&&(defined('DB_HOST'))))
	{
		$hash = md5($_SERVER['SERVER_NAME'].$_SERVER['SERVER_ADDR'].$_POST['host'].$_POST['user'].$_POST['type']);
		$file = new fileSystem('../fw_headers/mysql-config.php');
		$file->write('<?php
/*~ mysql-config.php
.---------------------------------------------------------------------------.
|  Software: '.$afterBootUp[0]->info['NME'].' SQL Config                                        |
|   Version: '.$afterBootUp[0]->info['VSN'].'                                                       |
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
	chmod("../", 0777);
	if(!file_exists('../yourcode.php'))
	{
		$file = new fileSystem('../yourcode.php');
	
		$file->write('<?php
	$seo = new headerSeo;
	$seo->encode();
	$seo->title($core->coreSencillo->info["FWK"]." - Example page");
	$seo->owner("'.$_POST['user-new-name'].', '.$_POST['user-new-mail'].'");
	$seo->bootstrapDefs();
	echo $seo->save();

	$translate = new translate("translate.json","en");
	require_once("./fw_templates/welcome.default.screen.php");
?>');

		$file = new fileSystem('../firststart.json');
		$json = json_encode(array(	'time'=>date("H:i:s"),
									'date'=>date("Y-m-d"),
									'email'=>$_POST['user-new-mail'],
									'PHP' =>phpversion(),
									'SYSTEM'=>$afterBootUp[0]->info['FWK'],
									'hash'=>$hash
		));
		$file->write($json);
	}
	if(!defined('DB_USER'))
	{
		$file = new fileSystem('../.htaccess');
		$file->write('# Create with '.$afterBootUp[0]->info['FWK'].'.
# Image cache
<IfModule mod_expires.c>
    ExpiresActive on

    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
</IfModule>

RewriteCond %{SERVER_PORT} ^443$
RewriteRule ^(.*)$ http://'.$_SERVER['SERVER_NAME'].'/$1 [L,R=301]

# Rewrite URLs
RewriteEngine on
RewriteBase /

# Best URLs
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule ^(.*)$ index.php?p=$1 [L,QSA]

# opensencillo.com -> www.opensencillo.com
RewriteCond %{HTTP_HOST} !^'.$_SERVER['SERVER_NAME'].'$ [NC]
RewriteRule ^(.*)$ http://'.$_SERVER['SERVER_NAME'].'/$1 [L,R=301]');
	}
	
	chmod("../fw_core/", 0700);
	chmod("../fw_cache/", 0700);
	chmod("../fw_headers/", 0700);
	chmod("../fw_modules/", 0700);
	chmod("../fw_libraries/", 0700);
	chmod("../fw_script/", 0700);
	chmod("../", 0700);
	require("../fw_headers/mysql-config.php");
	require("../fw_headers/main-config.php");
	require("./core_sql.php");
	require("../fw_libraries/login.management.logman.php");
	require("../fw_libraries/test.tool.framework.php");
		
	if(!defined('DB_USER'))
	{
		$delinsql='
		SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
		';
		$mysql->write($delinsql);
		$delinsql='
		SET time_zone = "+00:00";
		';
		$mysql->write($delinsql);
	}
	$delinsql='
	CREATE TABLE IF NOT EXISTS `console` (
	  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	  `time` datetime NOT NULL,
	  `title` varchar(25) NOT NULL,
	  `data` varchar(255) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MEMORY  DEFAULT CHARSET=utf8 MAX_ROWS=10000 AUTO_INCREMENT=0;
	';
	$mysql->write($delinsql);

	$delinsql='
	CREATE TABLE IF NOT EXISTS `country` (
	  `id` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
	  `id2` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
	  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
	';
	$mysql->write($delinsql);

	$delinsql='
	CREATE TABLE IF NOT EXISTS `login` (
	  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	  `userid` bigint(20) NOT NULL,
	  `sessionid` longtext NOT NULL,
	  `expiration` int(11) NOT NULL,
	  `perm` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
	';
	$mysql->write($delinsql);

	$delinsql='
	CREATE TABLE sencillo_cache (
	   CACHEKEY varchar(255) NOT NULL,
	   CACHEEXPIRATION int(11) NOT NULL,
	   GZDATA blob,
	   DATASIZE int(11),
	   DATACRC int(11),
	   PRIMARY KEY (CACHEKEY)
	 );
	';
	$mysql->write($delinsql);

	$delinsql='
	CREATE TABLE IF NOT EXISTS `virtual_system_config` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `function` varchar(25) NOT NULL,
	  `command` varchar(25) NOT NULL,
	  `commander` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
	';
	$mysql->write($delinsql);
	$mysql->close();
	
	$logman=new logMan($DBHost,$DBName,$DBUser,$DBPass);
	$mysql=new mysqlInterface($DBHost,$DBName,$DBUser,$DBPass);
	$mysql->config();
	$mysql->connect();
	
	if(!defined('DB_USER'))
	{
		$delinsql=array(
		'virtual_system_config'=>array(
			'id'=>"''",
			'function'=>'superuser',
			'command'=>$_POST['user-new-name'],
			'commander'=>0
		));
		$mysql->insert($delinsql);
	
	
		$delinsql=array(
		'virtual_system_config'=>array(
			'id'=>"''",
			'function'=>'superpass',
			'command'=>md5($_POST['user-new-pass']),
			'commander'=>0
		));
		$mysql->insert($delinsql);
	
		$delinsql=array(
		'virtual_system_config'=>array(
			'id'=>"''",
			'function'=>'supermail',
			'command'=>$_POST['user-new-mail'],
			'commander'=>0
		));
		$mysql->insert($delinsql);
	
		$delinsql=array(
		'virtual_system_config'=>array(
			'id'=>"''",
			'function'=>'systemhash',
			'command'=>$hash,
			'commander'=>0
		));
		$mysql->insert($delinsql);
	
		$delinsql=array(
		'virtual_system_config'=>array(
			'id'=>"''",
			'function'=>'servername',
			'command'=>$_SERVER['SERVER_NAME'],
			'commander'=>0
		));
		$mysql->insert($delinsql);
	
		$delinsql=array(
		'virtual_system_config'=>array(
			'id'=>"''",
			'function'=>'htaccess_config',
			'command'=>'default',
			'commander'=>0
		));
		$mysql->insert($delinsql);
	}
	else
	{
		$delinsql=array(
		'virtual_system_config'=>array(
			'id'=>"''",
			'function'=>'htaccess_config',
			'command'=>'sams',
			'commander'=>0
		));
		$mysql->insert($delinsql);
	}
	
	$delinsql=array(
	'virtual_system_config'=>array(
		'id'=>"''",
		'function'=>'phpversion',
		'command'=>phpversion(),
		'commander'=>0
	));
	$mysql->insert($delinsql);
	
	$dir  = '../fw_libraries';
	$scan = array_diff(scandir($dir),array('..', '.'));
	$json = json_encode($scan);
	$hash = md5($json);
	foreach($scan as $key=>$val)
	{
		$delinsql=array(
		'virtual_system_config'=>array(
			'id'=>"''",
			'function'=>"lib:$key",
			'command'=>"$val",
			'commander'=>0
		));
		$mysql->insert($delinsql);
	}
	$delinsql=array(
	'virtual_system_config'=>array(
		'id'=>"''",
		'function'=>"lib_count",
		'command'=>sizeof($scan),
		'commander'=>0
	));
	$mysql->insert($delinsql);
	
	$delinsql=array(
	'users'=>array(
		'id'=>"''",
		'sign'=>"'first_use'",
		'active'=>0,
		'login'=>"'".$_POST['user-new-name']."'",
		'pass'=>"'".md5($_POST['user-new-pass'])."'",
		'email'=>"'".$_POST['user-new-mail']."'",
		'fname'=>"''",
		'lname'=>"''",
		'perm'=>1111,
		'ip'=>"'".$_SERVER['REMOTE_ADDR']."'",
		'agent'=>"'".$_SERVER['HTTP_USER_AGENT']."'",
		'date'=>'DATE(NOW())',
		'time'=>'TIME(NOW())',
	));
	$mysql->insert($delinsql,false);
	$mysql->execute();
}
require_once '../fw_templates/installer.main.screen.php';
?>
