<?php
/*~ basicstrap.php
.---------------------------------------------------------------------------.
|  Software: OpenSencillo Basic Bootstrap                                   |
|   Version: 2015.109                                                       |
|   Contact: mail@phorvath.com                                              |
| ------------------------------------------------------------------------- |
|    Author: Bc. Peter Horváth (original founder)                           |
| Copyright (c) 2015, Bc. Peter Horváth. All Rights Reserved.               |
| ------------------------------------------------------------------------- |
|   License: Distributed under the General Public License (GPL)             |
|            http://www.gnu.org/licenses/gpl-3.0.html                       |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'---------------------------------------------------------------------------'
~*/
error_reporting(E_ERROR | E_PARSE);
if((defined('DB_USER'))&&(defined('DB_NAME'))&&(defined('DB_PASS'))&&(defined('DB_HOST')))
{
	class database
	{
		const host = DB_HOST;
		const name = DB_NAME;
		const user = DB_USER;
		const pass = DB_PASS;
		const type = "sams";
		const hash = "sams";
		const cache= "0";
	}
	
	require_once(__DIR__ . "/fw_core/core_interface.php");
	require(__DIR__ . "/fw_core/core_sql.php");
	require(__DIR__ . "/fw_core/core_functions.php");
	require(__DIR__ . "/fw_libraries/lib_identificator.php");
}
else
{
	include(__DIR__ . "/fw_headers/mysql-config.php");
	include(__DIR__ . "/fw_headers/main-config.php");
	require_once(__DIR__ . "/fw_core/core_interface.php");
	require(__DIR__ . "/fw_core/core_sql.php");
	require(__DIR__ . "/fw_headers/session.php");
	require(__DIR__ . "/fw_headers/cookies.php");
	require(__DIR__ . "/cache.php");
	require(__DIR__ . "/fw_core/core_functions.php");
	require(__DIR__ . "/fw_libraries/lib_identificator.php");
}

$inc=new library;
$inc->start();
$paths = $inc->exportPath();
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

if(PAGE=='admin')
{
	require_once(__DIR__ . "/fw_core/core_admin.php");
}
?>
