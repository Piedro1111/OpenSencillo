<?php
/**
 * Login management
 * @name logMan
 * @version 2015.005
 * @category Sencillo Library
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/licenses/gpl-3.0.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class logMan extends mysqlEdit
{
	protected $log=array();
	protected $status=array();
	
	public function __construct()
	{
		parent::__construct(database::host,database::name,database::user,database::pass);
		$this->log['server']=$_SERVER['SERVER_NAME'];
		$this->log['request']=$_SERVER['REQUEST_URI'];
		$this->log['port']=$_SERVER['REMOTE_PORT'];
		$this->log['agent']=$_SERVER['HTTP_USER_AGENT'];
		$this->log['referer']=$_SERVER['HTTP_REFERER'];
		$this->log['external_ip']=$_SERVER['REMOTE_ADDR'];
		$this->status=array(
			'called'=>$_POST['atype'],
			'date'=>date('Y-m-d'),
			'time'=>date('H:i:s')
		);
		$this->log['database']=array('host'=>database::host,
									 'name'=>database::name,
									 'user'=>database::user,
									 'pass'=>database::pass);
		$this->install();
	}
	
	/**
	 * Install logMan if table not exist
	 * 
	 * @return bool
	 *
	 */
	final public function install()
	{
		try
		{
            $this->newColumn("user_id","INT(1)");
            $this->newColumn("code","VARCHAR(5)");
			$this->newColumn("param","INT(1)");
			$this->newColumn("expire","DATETIME");
            $this->createTable("usersPasswordCodes");
            
			$this->newColumn("sign","TEXT");
			$this->newColumn("active","INT(1)");
			$this->newColumn("login","VARCHAR(255)");
			$this->newColumn("pass","VARCHAR(255)");
			$this->newColumn("email","VARCHAR(255)");
			$this->newColumn("fname","VARCHAR(255)");
			$this->newColumn("lname","VARCHAR(255)");
			$this->newColumn("perm","INT(4)");
			$this->newColumn("ip","VARCHAR(20)");
			$this->newColumn("agent","TEXT");
			$this->newColumn("date","VARCHAR(20)");
			$this->newColumn("time","VARCHAR(20)");
			$this->createTable("users");
			$email=$this->output("`function`='superemail'","`id` ASC",1);
			$name=$this->output("`function`='superuser'","`id` ASC",1);
			$pass=$this->output("`function`='superpass'","`id` ASC",1);
			$this->createSuperUser($email['line'][0][0],$name['line'][0][0],$pass['line'][0][0]);
			return true;
		}
		catch(Exception $e)
		{
			return false;
		}
	}
	
	/**
	 * Update perm in logMan
	 * 
	 * @param string
	 * @param int (1000~1111)
	 * 
	 * @return int OR false
	 */
	final public function editPerm($login=null,$perm=null)
	{
		if(isset($login))
		{
			if((is_numeric($perm))&&($perm<=1111))
			{
				$this->set("perm",$perm);
				$this->update("`login`=".$this->log['user']);
				unset($this->log['perm']);
				$this->log['perm']=$perm;
				return $this->log['perm'];
			}
			else
			{
				return false;
			}
		}
		else
		{
			$this->set("perm",$perm);
			$this->update("`login`=".$login);
		}
	}
	
	/**
	 * Returned actual user permission
	 * 
	 * @return int(4)
	 */ 
	final public function getPerm()
	{
		return $this->log['perm'];
	}
	
	/**
	 * Create admin user in database
	 *
	 * @param array $_POST
	 *
	 * @return array $this->status
	 */
	final public function createSuperUser($email,$name,$pass)
	{
		$this->openTable('users');
		if(filter_var($email,FILTER_VALIDATE_EMAIL))
		{
			$user=$this->output("`login`='".$name."'","`id` ASC",1);
			if($user['line'][1][0]==null)
			{
				try
				{
					$this->insert("'first_use',0,'".strtolower($name)."','".$pass."','".strtolower($email)."','','',1111,'".$this->log['external_ip'].":".$this->log['port']."','".$this->log['agent']."',DATE(NOW()),TIME(NOW())");
					$this->status['status']='ok';
					$this->status['code']=200;
				}
				catch(Exception $e)
				{
					$this->status['status']='failed';
					$this->status['code']=417;
				}
			}
			else
			{
				$this->status['status']='exist';
				$this->status['code']=409;
			}
		}
		else
		{
			$this->status['status']='invalid';
			$this->status['code']=403;
		}
		return $this->status;
	}
	
	/**
	 * Create new user in database
	 * 
	 * [@param array $_POST]
     * @param bool $onlyCheckUser if true set ereg to simulation mode (no insert query)
	 * 
	 * @return array $this->status
	 */
	final public function ereg($onlyCheckUser=false)
	{
		$this->openTable('users');
	    if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
	    {
	    	$user=$this->output("`login`='".$_POST['email']."'","`id` ASC",1);
	    	if($user['line'][1][0]==null)
    	    {
    	        try 
    	        {
                    if($onlyCheckUser===false)
                    {
                        $this->insert("'first_use',0,'".strtolower($_POST['email'])."',MD5('".$_POST['pass']."'),'".strtolower($_POST['email'])."','".$this->clean(ucwords(strtolower($_POST['fname'])))."','".$this->clean(ucwords(strtolower($_POST['lname'])))."',1000,'".$this->log['external_ip'].":".$this->log['port']."','".$this->log['agent']."',DATE(NOW()),TIME(NOW())");
                    }
    	            $this->status['status']='ok';
    	            $this->status['code']=200;
    	        }
    	        catch(Exception $e)
    	        {
    	        	$this->status['status']='failed';
    	        	$this->status['code']=417;
    	        }
    	    }
    	    else 
    	    {
    	    	$this->status['status']='exist';
    	    	$this->status['code']=409;
    	    }
	    }
	    else 
	    {
	        $this->status['status']='invalid';
	        $this->status['code']=403;
	    }
	    return $this->status;
	}
	
	/**
	 * Login with ajax
	 * 
	 * @param array $ajax
	 * 
	 * @return array $this->status
	 */
	final public function login($ajax)
	{
		$this->openTable('users');
	    if(filter_var($ajax['email'],FILTER_VALIDATE_EMAIL))
	    {
	    	if($this->output("`login`='".strtolower($ajax['email'])."' AND `pass`=MD5('".$ajax['pass']."')","`id` ASC",1)!=false)
    	    {
    	        $this->status['status']='authorized';
    	        $this->status['code']=202;
    	        $this->status['user']=$this->output("`login`='".strtolower($ajax['email'])."' AND `pass`=MD5('".$ajax['pass']."')","`id` ASC",1);
    	        
    	        $this->addSessionData('userid',$this->status['user']['line'][1][0]);
    	        $this->addSessionData('login',$this->status['user']['line'][1][3]);
    	        $this->addSessionData('email',$this->status['user']['line'][1][5]);
    	        $this->addSessionData('perm',$this->status['user']['line'][1][8]);
    	        $this->addSessionData('sessionid',$this->log['session']['id']);
    	        $this->addSessionData('start',date('Y-m-d H:i:s'));
    	        if($this->status['user']['line'][1][1]=='first_use')
    	        {
    	            $this->addSessionData('tutorial',true);
    	        }
    	        else
    	        {
    	            $this->addSessionData('tutorial',false);
    	        }
    	        $this->update('`id`='.$this->status['user']['line'][1][0],"`sign`='".$this->getSessionData('sessionid')."',`ip`='".$this->log['external_ip'].":".$this->log['port']."',`agent`='".$this->log['agent']."',`date`='".$this->status['date']."',`time`='".$this->status['time']."'");
    	        
    	        unset($this->status['user']['line']);
    	    }
    	    else 
    	    {
    	    	$this->status['status']='unauthorized';
    	    	$this->status['code']=404;
    	    }
	    }
	    else 
	    {
	        $this->status['status']='invalid';
	        $this->status['code']=403;
	    }
	    return $this->status;
	}
	
	/**
	 * Check whether session
	 * 
	 * @param bool $signal
	 * @return boolean|Ambigous <multitype:number , multitype:>
	 */
	final public function checkSession($signal=false)
	{
		$this->openTable('users');
		$browser = ($this->getSessionData('sessionid') ? array("code"=>200) : $this->login($_POST));
		$server  = $this->output("`id`=".$this->getSessionData('userid'));
		
		if(!$signal)
		{
			return (($server['line'][1][1]===$this->getSessionData('sessionid'))&&($browser["code"]<300)&&($server['line'][1][8]===$this->getSessionData('perm')) ? true : false);
		}
		else 
		{
			return (($server['line'][1][1]===$this->getSessionData('sessionid'))&&($browser["code"]<300)&&($server['line'][1][8]===$this->getSessionData('perm')) ? $browser : array("code"=>404));
		}
	}
	
	/**
	 * Create default login system
	 * @param object $translate
	 * @param object $seo
	 * @return number
	 */
	public function basicLogin($translate,$seo)
	{
		$this->createSession();
		if((is_object($translate))&&(is_object($seo)))
		{
			switch($_GET['p'])
			{
				case 'logout':
					$this->destroySession();
				case '':
					define('LOGIN_ERRMSG',"000:".$_SESSION['sessionid']);
					define('LOGIN_ACTION','/login');
					echo $seo->save();
					require_once 'fw_templates/login.default.screen.php';
					break;
				case 'login':
					$status = $this->checkSession(true);
					$seo->custom('<script type="text/javascript">console.log("Login status:'.$status["code"].'");</script>');
					switch($status['code'])
					{
						case 200:
						case 202:
							//login success
							define('LOGIN_ERRMSG',$status['code'].":".$_SESSION['sessionid'].":ok:user:".$this->getSessionData('userid'));
							echo $seo->save();
							require_once 'fw_templates/account.dafault.screen.php';
							break;
						default:
							//login failed
							$this->destroySession();
							define('LOGIN_ERRMSG',$status['code'].":".$_SESSION['sessionid'].":failed");
							define('LOGIN_ACTION','/login');
							echo $seo->save();
							require_once 'fw_templates/login.default.screen.php';
					}
					break;
				case 'ereg':
				case 'registration':
					//ereg
					$this->destroySession();
					define('LOGIN_ACTION','/registration');
					$status = $this->ereg();
					define('LOGIN_ERRMSG',$status['code'].":ereg");
					echo $seo->save();
					require_once 'fw_templates/ereg.default.screen.php';
					break;
			}
			return $status['code'];
		}
		else 
		{
			return 500;
		}
	}
	
	/**
	 * Create default admin login system
	 * @param object $translate
	 * @param object $seo
	 * @return number
	 */
	public function adminLogin($translate,$seo)
	{
		$this->createSession();
		if((is_object($translate))&&(is_object($seo)))
		{
			switch($_GET['p'])
			{
				case 'logout':
					$this->destroySession();
				case 'admin':
					$status = $this->checkSession(true);
					$seo->custom('<script type="text/javascript">console.log("Login status:'.$status["code"].'");</script>');
					switch($status['code'])
					{
						case 200:
						case 202:
							//login success
							define('LOGIN_ERRMSG',$status['code'].":".$_SESSION['sessionid'].":ok:user:".$this->getSessionData('userid'));
							echo $seo->save();
							require_once 'fw_templates/account.dafault.screen.php';
							break;
						default:
							//login failed
							$this->destroySession();
							define('LOGIN_ERRMSG',$status['code'].":".$_SESSION['sessionid'].":failed");
							define('LOGIN_ACTION','/login');
							echo $seo->save();
							require_once 'fw_templates/login.default.screen.php';
					}
					break;
			}
			return $status['code'];
		}
		else 
		{
			return 500;
		}
	}
	
	/**
	 * Add data to main login array
	 * @param string $name
	 * @param multitype $data
	 */
	final public function addToMainArray($name,$data)
	{
		$this->status[$name]=$data;
	}
	
	/**
	 * Convert main array to JSON export and print as AJAX response
	 */
	final public function ajaxSendJson()
	{
		print json_encode($this->status);
	}
	
	final public function addNewUser($pass,$perm)
	{
		
	}
	
	/**
	 * Create session data
	 * @return multitype array
	 */
	final public function createSession()
	{
		$this->log['session']=array('exist'=>session_start(),
									'id'=>hash("sha512",session_id().date("YmdHis")),
									'date'=>date('Y-m-d'),
									'time'=>date('H:i:s'));
		return $this->log['session'];
	}
	
	/**
	 * Destroy exist session
	 */
	final public function destroySession()
	{
		$this->update('`id`='.$this->getSessionData('userid'),"`sign`=NULL");
		unset($this->log['session']);
		session_destroy();
	}
	
	/**
	 * Store data in new session
	 * @param string $name
	 * @param string $data
	 * @return string
	 */
	final public function addSessionData($name,$data=null)
	{
		$_SESSION[$name]=$data;
		return $data;
	}
	
	/**
	 * Get data from session storage
	 * @param string $name
	 * @return multitype
	 */
	final public function getSessionData($name)
	{
		return $_SESSION[$name];
	}
	
    /**
     * @todo Login function
     * @param type $pass
     */
	final public function signIn($pass)
	{
		//TODO
	}
	
	/**
	 * Get all information about signed user
	 * 
	 * @return array
	 */
	final public function getSignedUser()
	{
		return $this->log;
	}
	
    /**
     * @todo logout function
     */
	final public function signOut()
	{
		//TODO
	}
	
    /**
     * Forgot / Reset password
     * @return array
     */
    final public function forgot()
    {
        $status = $this->ereg(true);
        if($status['code']===409)
        {
            $this->status = array('code'    => 200,
                                  'status'  => 'ok');
        }
        else
        {
            $this->status = array('code'    => 404,
                                  'status'  => 'not registered');
        }
        
        $this->status['confirm-code'] = substr(hash('crc32b',date('YmdHis')),0,5);
        return $this->status;
    }

    /**
	 * Remove all special characters
	 * @param string $string
	 * @return string
	 */
	final public function clean($string) 
	{
	    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
}
?>