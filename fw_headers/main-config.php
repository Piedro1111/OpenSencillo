<?php
/**
 * AUTOMATIC SYSTEM START-UP INFORMATION
 * DO NOT CHANGE IT!
 * 
 * @name Sencillo Config
 * @version 2015.005
 * @category config
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/licenses/gpl-3.0.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
define('PAGE',$_GET["p"]);
define("USER_IP",$_SERVER["REMOTE_ADDR"]);
define("USER_BROWSER",$_SERVER["HTTP_USER_AGENT"]);
if(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
{
	$protocol = 'https://';
}
else
{
	$protocol = 'http://';
}
define("PROTOCOL",$protocol);
?>
