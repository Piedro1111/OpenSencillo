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
// //Main information
// /*
//  * AUTOMATIC SYSTEM START-UP INFORMATION
//  * DO NOT CHANGE IT!
//  */
// $server=$_SERVER['SERVER_NAME'];
// $server=str_replace("www.", "", $server);
// $server="www.".$server;
// $domain=explode(".",$server);
// $complet_date=date("Ymd");//NOSEPARATE DATE
// $complet_time=date("His");//NOSEPARATE TIME
// $complet_page=$complet_date.$complet_time;
// $standard_date=date("d.m.Y");//EU DATE
// $standard_time=date("H:i:s");//EU TIME
// $us_date=date("Y/m/d");//US DATE
// /*
//  * MANUAL SYSTEM CONFIGURATION
//  * YOU CAN CHANGE IT
//  */
// $protocol="http";//SITE PROTOCOL
// $signature=null;

// //Homepage definition
// define("WEBNAME","");//Website name
// define("ADMINDESC","");//Homepage description

// //DB Server
// //$DBHost = "";

// //SQL access
// //$DBUser = "";
// //$DBName = "";
// //$DBPass = "";

// //SQL access 2 - main system log and stats
// $DBUser2 = "";
// $DBName2 = "";
// $DBPass2 = "";

// $checksum=$DBHost.$DBUser.$DBName.$DBPass.$_SERVER['SERVER_NAME'];
// $checksum2=$DBHost.$DBUser2.$DBName2.$DBPass2.$_SERVER['SERVER_NAME'];
// define("CHECKSUM",md5($checksum),true);
// define("CHECKSUM2",md5($checksum2),true);
// unset($checksum,$checksum2);
// $system_security=array("basics_vars"=>array(),"specials"=>array(), "name"=>array());
// $system_security["basics_vars"][0]=$DBHost;
// $system_security["basics_vars"][1]=$DBUser;
// $system_security["basics_vars"][2]=$DBName;
// $system_security["basics_vars"][3]=$DBPass;
// $system_security["basics_vars"][4]=md5(CHECKSUM);//server checksum
// $system_security["basics_vars"][5]=$DBHost;
// $system_security["basics_vars"][6]=$DBUser2;
// $system_security["basics_vars"][7]=$DBName2;
// $system_security["basics_vars"][8]=$DBPass2;
// $system_security["basics_vars"][9]=md5(CHECKSUM2);//server checksum2

// //Automatic user settings
// define("USER_GEO","---");
// define("USER_GEO_TWO","--");
// define("USER_IP",$_SERVER["REMOTE_ADDR"]);
// define("USER_BROWSER",$_SERVER["HTTP_USER_AGENT"]);

// //Site Access settings
// $SiteOffLine = 0;
// $SiteModules = 1;
// $SiteOnlyAdmin = 0;
// $SiteOAkey = "Sencillo";
// $SiteOAKcookies = 1;
// $SystemMail = "";
// $expire=time()+(3600/4);
// $OAkeyexpire=time()+(7200);
// $MAXexpire=time()+(7200);
// $SiteOffMessage='<body><center>Site is OFF</center></body>';
// $SiteErrorMsg='<body><center>Error '.$_GET['error'].'</center></body>';
// if($_GET['error']!="")
// {
// 	$SiteOffLine=1;
// 	$SiteOffMessage=$SiteErrorMsg;
// }
// $footbox='
// <!--
// /*
// * PROJECT:Sencillo Framework
// * SYSTEM: OpenSencillo
// * AUTHOR: Bc.Peter Horváth
// */
// -->
// ';

// //Virtual config
// /*
//  * DO NOT CHANGE IT!
//  */
// $sql="SELECT * FROM virtual_system_config";
// $con=mysql_connect($DBHost, $DBUser, $DBPass);
// mysql_select_db($DBName, $con);
// $result=mysql_query($sql);

// while($row=mysql_fetch_array($result))
// {
// 	if($row['function']=="siteoffline")
// 	{
// 		$SiteOffLine=(int)$row['command'];
// 	}
// 	if($row['function']=="siteonlyadmin")
// 	{
// 		$SiteOnlyAdmin=$row['command'];
// 	}
// 	if($row['function']=="systemmail")
// 	{
// 		$SystemMail=$row['command'];
// 	}
// 	if($row['function']=="siteoffmessage")
// 	{
// 		$SiteOffMessage=$row['command'];
// 	}
// 	if($row['function']=="sitemodules")
// 	{
// 		$SiteModules=$row['command'];
// 	}
// 	if($row['function']=="hideninfo")
// 	{
// 		$footbox=$row['command'];
// 	}
// }
// mysql_close($con);
?>
