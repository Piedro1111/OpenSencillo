<?php
/*~ session.php
.---------------------------------------------------------------------------.
|  Software: Sencillo Session                                               |
|   Version: 2014.002                                                       |
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
/**
 * Cookies manipulation class
 * @name Sencillo Session
 * @version 2014.002
 * @category accessories
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class sessionManager
{
	private $i;
	private $smarray;
	
	/**
	 * Create new session or reload old session
	 */
	public function __construct()
	{
		session_start();
	}
	
	/**
	 * Get stored data from session
	 * @param string $name
	 * @return mixed
	 */
	public function sm_get($name)
	{
		if(is_array($name))
		{
			$this->i = 0;
			$this->smarray = array();
			while(sizeof($name)>$this->i)
			{
				$this->smarray[$this->i] = $_SESSION[$name[$this->i++]];
			}
			return $this->smarray;
		}
		else
		{
			return $_SESSION[$name];
		}
	}
	
	/**
	 * Session destroy
	 */
	public function sm_destroy()
	{
		session_destroy();
	}
}

/**
 * Cookies manipulation class
 * @todo Delete all non objective code and implement it to this class
 * @name Sencillo Session
 * @version 2014.002
 * @category accessories
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class loginManager extends sessionManager
{
	private $current_time;
	private $sql;
	
	/**
	 * Create time settings for loginManager
	 */
	public function __construct()
	{
		$this->current_time = array('year'=>date('Y'),
									'month'=>date('m'),
									'day'=>date('d'),
									'hour'=>date('H'),
									'minute'=>date('i'),
									'second'=>date('s'),
									'session'=>date('YmdHis'));
	}
	
	/**
	 * Install loginManager class
	 */
	public function lm_install()
	{
		$this->sql='
		CREATE TABLE IF NOT EXISTS `login` (
			`id` bigint(20) NOT NULL AUTO_INCREMENT,
			`userid` bigint(20) NOT NULL,
			`sessionid` longtext NOT NULL,
			`expiration` int(11) NOT NULL,
			`perm` int(11) NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
		';
		$mysql->openTable('login');
		$mysql->write($this->sql);
		$this->sql='
		CREATE TABLE IF NOT EXISTS `users` (
			`userid` bigint(20) NOT NULL AUTO_INCREMENT,
			`name` longtext NOT NULL,
			`pass` longtext NOT NULL,
			`perm` int(4) NOT NULL,
			PRIMARY KEY (`userid`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
		';
		$mysql->openTable('users');
		$mysql->write($this->sql);
	}
	
	/**
	 * Add user
	 * @param string $name
	 * @param string $pass
	 * @param integer $perm
	 */
	public function lm_addUser($name,$pass,$perm=1000)
	{
		$mysql->openTable('users');
		$mysql->insert("'name',md5('$pass'),$perm");
	}
}
/*
 *
 * 	error = 0; //system: OK - access granted
 * 	error = null; //system: UNKNOWN STATUS, system continued
 * 	error = 1; //system: I/O - ERROR email not exist
 * 	error = 2; //system: I/O - ERROR password error
 * 	error = 3; //system: DB - ERROR login structured data failed - AntiHack attention
 *
 *  Session manipulation:
 * 	?s=exit //system get status NULL and unset access signature - system go to logout mode
 *
 */
//echo("<script>alert('Country:".USER_GEO.";User:".$_SESSION['userid'].";Status:".$error.";Cookies:[".$cookie1."],[".$cookie2."];Exp:".$LoginExp.";SessionID:".$_SESSION['sessionid']."');</script>");
?>
