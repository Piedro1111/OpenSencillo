<?php
/*~ cookies.php
.---------------------------------------------------------------------------.
|  Software: Sencillo Cookies                                        		|
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
// if((isset($cookie1))&&(isset($cookie2))&&(isset($LoginExp))&&($LoginExp>3600))
// {
// 	setcookie("ulid", $cookie1, $LoginExp);
// 	setcookie("uid", $cookie2, $LoginExp);
// }
// $SiteStatusMessage=null;
// //OAKEY subsystem
// if($SiteOnlyAdmin==1)
// {
// 	if(($SiteOAkey==$_GET['oakey']) || ($_COOKIE['oakey']==$SiteOAkey))
// 	{
// 		if(($SiteOAKcookies==1) && ($_COOKIE['oakey']!=$SiteOAkey)){setcookie("oakey", $_GET['oakey'], $OAkeyexpire);}
// 		$SiteStatusMessage="<span class='oakeyunlock'>Access granted by OAKEY=$SiteOAkey. Access only for you!</span><br>";
// 	}
// 	else
// 	{
// 		$SiteOffLine=1;
// 	}
// }
// if($SiteOffLine==1)
// {
// 	die("<html>$SiteOffMessage $footbox</html>");
// }

/**
 * Cookies manipulation class
 * @name Sencillo Cookies
 * @version 2014.002
 * @category accessories
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class getCookies
{
	private $name;
	private $expiration;
	private $data;

	/**
	 * Create expiration time
	 */
	public function __construct()
	{
		$this->expiration = time()+3600;
	}
	
	/**
	 * Set expiration time
	 * @param integer $time
	 */
	public function setExpiration($time)
	{
		$this->expiration = time()+($time*60);
	}
	
	/**
	 * Add cookie
	 * @param string $name
	 * @param mixed $data
	 */
	public function addCookie($name,$data)
	{
		$this->name = $name;

		$this->data = $data;
		setcookie($this->name,$this->data,$this->expiration);
	}
	
	/**
	 * Remove cookie
	 * @param string $name
	 */
	public function removeCookie($name)
	{
		$this->name = $name;

		setcookie($this->name,"",time()-3600);
	}
	
	/**
	 * Get saved cookie by name
	 * @name string $name
	 * @return mixed
	 */
	public function getCookie($name)
	{
		$this->name = $name;

		return $_COOKIE[$this->name];
	}
}
?>
