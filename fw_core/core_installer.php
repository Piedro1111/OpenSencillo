<?php
/*~ core_installer.php
.---------------------------------------------------------------------------.
|  Software: Sencillo SQL Installer                                         |
|   Version: 2015.001                                                       |
|   Contact: ph@mastery.sk                                                  |
| ------------------------------------------------------------------------- |
|    Author: Bc. Peter Horváth (original founder)                           |
| Copyright (c) 2014, Bc. Peter Horváth. All Rights Reserved.               |
| ------------------------------------------------------------------------- |
|   License: Distributed under the General Public License (GPL)             |
|            http://www.gnu.org/copyleft/gpl.html                           |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'---------------------------------------------------------------------------'
~*/
/**
 * Core installer
 * @name Sencillo SQL Installer
 * @version 2015.002
 * @category core
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
require_once("./core_functions.php");
$i=0;
$afterBootUp=array();
$afterBootUp[$i++]=new coreSencillo;
$seo=new headerSeo;
$seo->encode();
$seo->title('Sencillo - Installer');
$seo->owner('Bc. Peter Horváth');
$seo->save();
echo $seo->seo;
$PHPversion=explode(".",phpversion());
if(($_GET['install']=='true')&&($PHPversion[0]>=5))
{
	chmod("../fw_headers/", 0777);
	if(($_POST['host']!="")&&($_POST['user']!="")&&($_POST['name']!="")&&($_POST['pass']!=""))
	{
		$file = new fileSystem('../fw_headers/mysql-config.php');
		$file->write('<?php
/*~ mysql-config.php
.---------------------------------------------------------------------------.
|  Software: Sencillo SQL Config                                            |
|   Version: 2015.002                                                       |
|   Contact: ph@mastery.sk                                                  |
| ------------------------------------------------------------------------- |
|    Author: Bc. Peter Horváth (original founder)                           |
| Copyright (c) 2014, Bc. Peter Horváth. All Rights Reserved.               |
| ------------------------------------------------------------------------- |
|   License: Distributed under the General Public License (GPL)             |
|            http://www.gnu.org/copyleft/gpl.html                           |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
´---------------------------------------------------------------------------´
~*/
//DB Server
$DBHost = "'.$_POST['host'].'";
//SQL access
$DBUser = "'.$_POST['user'].'";
$DBName = "'.$_POST['name'].'";
$DBPass = "'.$_POST['pass'].'";
//SQL type
$DBType = "'.$_POST['type'].'";
//Cache
$QUICKCACHE_ON = '.$_POST['cache'].';
?>');
	}
	chmod("../", 0777);
	if(!file_exists('../yourcode.php'))
	{
		$file = new fileSystem('../yourcode.php');
	
		$file->write('<?php
	//write your PHP code here
?>');
		$file = new fileSystem('../firststart.json');
		$json = json_encode(array(	'time'=>date("H:i:s"),
									'date'=>date("Y-m-d"),
									'PHP' =>phpversion(),
									'SYSTEM'=>'OpenSencillo 2015'
		));
		$file->write($json);
	}
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

	$delinsql='
	SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
	';
	$mysql->write($delinsql);
	$delinsql='
	SET time_zone = "+00:00";
	';
	$mysql->write($delinsql);

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
}
require_once '../fw_templates/installer.main.screen.php';
?>
