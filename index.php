<?php
/*~ index.php
.---------------------------------------------------------------------------.
|  Software: OpenSencillo Index                                             |
|   Version: 2015.109                                                       |
|   Contact: info@opensencillo.com                                          |
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
if(file_exists('yourcode.php'))
{
	require(__DIR__ . 'basicstrap.php');
	require(__DIR__ . 'yourcode.php');
}
else 
{
	header('Location: http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'fw_core/core_installer.php');
}
?>
