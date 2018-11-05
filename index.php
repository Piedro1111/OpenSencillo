<?php
/*~ index.php
.---------------------------------------------------------------------------.
|  Software: OpenSencillo Index                                             |
|   Version: 2018.110                                                       |
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
if((strpos($_GET['p'],"'")!==false)||(strpos($_GET['p'],'"')!==false))
{
	die('403 Incorrect URL');
}
else
{
	if(file_exists('yourcode.php'))
	{
		$lifetime=3600*8;
		session_start();
		setcookie(session_name(),session_id(),time()+$lifetime);
		require(__DIR__ . '/basicstrap.php');
		$core = new coreSencillo;
		$data = $core->version_info();
		
		setcookie('OpenSencillo',$data['HPE'],time()+$lifetime);
		require(__DIR__ . '/yourcode.php');
	}
	else 
	{
		header('Location: http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'install.php');
	}
}

?>
