<?php
/*~ main-config.php
.---------------------------------------------------------------------------.
|  Software: Sencillo Config                                                |
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
//Main information
/**
 * AUTOMATIC SYSTEM START-UP INFORMATION
 * DO NOT CHANGE IT!
 * 
 * @name Sencillo Config
 * @version 2015.002
 * @category config
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
define('PAGE',$_GET['p']);
define("USER_IP",$_SERVER["REMOTE_ADDR"]);
define("USER_BROWSER",$_SERVER["HTTP_USER_AGENT"]);
?>
