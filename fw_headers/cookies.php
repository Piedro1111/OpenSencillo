<?php
/*~ cookies.php
.---------------------------------------------------------------------------.
|  Software: Sencillo Cookies                                        		|
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
/**
 * Cookies manipulation class
 * @name Sencillo Cookies
 * @version 2015.002
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
