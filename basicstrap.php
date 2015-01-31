<?php
/*~ basicstrap.php
.---------------------------------------------------------------------------.
|  Software: Sencillo Basic Bootstrap                                       |
|   Version: 2015.003                                                       |
|   Contact: ph@mastery.sk                                                  |
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
include("./fw_headers/mysql-config.php");
include("./fw_headers/main-config.php");
require("./fw_core/core_sql.php");
require("./fw_headers/session.php");
require("./fw_headers/cookies.php");
require("./cache.php");
require("./fw_core/core_functions.php");
require("./fw_libraries/lib_identificator.php");
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

?>
