<?php
/*~ basicstrap.php
.---------------------------------------------------------------------------.
|  Software: Sencillo Basic Bootstrap                                       |
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
'---------------------------------------------------------------------------'
~*/
include("./fw_headers/mysql-config.php");
include("./fw_headers/main-config.php");
require("./fw_core/core_sql.php");
require("./fw_headers/session.php");
require("./cache.php");
require("./fw_core/core_functions.php");
require("./fw_libraries/lib_identificator.php");
$inc=new library;
$inc->start();
foreach($inc->lib['path'] as $val)
{
	require_once("$val");
}

?>
