<?php
class pihome
{
	private $url;
	private $urlprefix;
	private $port;
	private $css;
	private $js;
	private $stn;
	private $protocol;
	private $seo;
	private $linkmngr;
	private $logman;
	private $template;
	
	private $ExtHDD;
	private $HDDerr;
	private $Condensation;
	private $CondensationSTS;
	private $CondensationLVL;
	private $err;
	private $CPUtemperature;
	private $playerCPUtemperature;
	private $pcstatus;
	private $pcstatusjson;
	
	private $usertype;
	private $mainmenu;
	
	private $mysqlinterface;
	
	final public function __construct()
	{
		error_reporting(E_ERROR | E_PARSE);
		session_start();
		
		$_SESSION['count']++;
		
		$this->protocol = 'http'; 
		$this->url = 'pihome';
		$this->urlprefix = $this->url;
		$this->port = ':'.$_SERVER['SERVER_PORT'];
		$this->template = '/fw_templates/additional/rpi/production';
		$this->css = $this->urlprefix.$this->template;
		$this->js = $this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/fw_templates/additional/rpi/production';
		$this->stn = $this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/shutdown';
		
		$this->seo = new headerSeo;
		$this->linkmngr = new url;
		$this->logman = new logMan;
		
		$this->mysqlinterface = new mysqlinterface;
		$this->mysqlinterface->config();
		$this->mysqlinterface->connect();
		
		//main
		$this->seoGenerator();
		$this->defaultHead(PAGE);
		$this->permDecode();
		$this->mainLogic();
	}
	
	/**
	 * seoGenerator - create all data for meta tag
	 */
	final private function seoGenerator()
	{
		$this->seo->encode();
		$this->seo->title("{$this->url}");
		$this->seo->owner("Peter Horváth, phorvath.com");
		$this->seo->custom("<script>var server_name='{$this->protocol}://{$_SERVER['SERVER_NAME']}{$this->port}/{$this->url}';</script>");
		$this->seo->custom("<meta http-equiv='X-UA-Compatible' content='IE=edge'>");
		$this->seo->custom("<meta http-equiv='cache-control' content='no-cache'>");
		$this->seo->custom("<meta http-equiv='expires' content='-1'>");
		$this->seo->custom("<meta http-equiv='pragma' content='no-cache'>");
		$this->seo->bootstrapDefs();
		$this->seo->style("{$this->protocol}://{$_SERVER['SERVER_NAME']}{$this->port}/{$this->css}/fonts/css/font-awesome.min.css");
		$this->seo->style("{$this->protocol}://{$_SERVER['SERVER_NAME']}{$this->port}/{$this->css}/css/animate.min.css");
		$this->seo->style("{$this->protocol}://{$_SERVER['SERVER_NAME']}{$this->port}/{$this->css}/css/custom.css");
		$this->seo->style("{$this->protocol}://{$_SERVER['SERVER_NAME']}{$this->port}/{$this->css}/css/icheck/flat/green.css");
		$this->seo->custom('
		<!--[if lt IE 9]>
			<script src="../assets/js/ie8-responsive-file-warning.js"></script>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		');
		$this->seo->script("{$this->js}/js/extend_js/ext.js");
	}
	
	/**
	 * permDecode - decode basic permission if parameter perm is integer.
	 * 
	 * @param int $perm example $this->permDecode(1100)
	 * 
	 * @return string
	 */
	final private function permDecode($perm=false)
	{
		if($perm===false)
		{
			$perm=$_SESSION['perm'];
		}
		switch($perm)
		{
			case '0':
			case '0000':
			case '1000':
				$this->usertype = 'ban';
				break;
			case '1100':
				$this->usertype = 'user';
				break;
			case '1110':
				$this->usertype = 'vip';
				break;
			case '1111':
				$this->usertype = 'admin';
				break;
			default:
				$this->usertype = 'unknown';
		}
		return $this->usertype;
	}
	
	/**
	 * setup different head on different pages
	 * @param string $PAGE
	 */ 
	final private function defaultHead($PAGE)
	{
		$filtered = explode('?',$PAGE);
		$PAGE = $filtered[0];
		switch($PAGE)
		{
			case 'phpinfo':
				if(($this->logman->checkSession())&&($_SESSION['perm']>=1110))
				{
					echo phpinfo();
					die;
				}
			break;
			case 'logout':
				$this->mysqlinterface->update(array('users'=>array(
					'condition'=>array(
						'`id`='.$this->logman->getSessionData('userid'),
					),
					'set'=>array(
						'sign'=>'logout'
					)
				)));
				$this->mysqlinterface->execute();
				$this->logman->destroySession();
				header('Location: '.$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/');
			break;
			case 'users/user/save':
			case 'profile/save':
				$status = false;
				if($_SESSION['perm']>=1111)
				{
					$this->profileUpdate($this->profile('login'));
					$status = true;
					header('Location: '.$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/users/view?u='.$_GET['u']);
				}
				else
				{
					$this->profileUpdate($this->logman->getSessionData('login'));
					$status = true;
					header('Location: '.$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/profile');
				}
			break;
			default:
				echo $this->seo->save();
		}
	}
	
	/**
	 * Main logic
	 */ 
	final private function mainLogic()
	{
		if($this->logman->checkSession())
		{
			if($_SESSION['perm']>=1100)
			{
				$this->getExtHDDstatus();
				$this->getCondensation();
				$this->getTemperatures();
				
				$this->linkmngr->addUrl('','dashboard_pi_page.html.php');
				$this->addMenuItem('Dashboard','','tachometer',1100);
			}
			
			if($_SESSION['perm']>=1110)
			{
				$this->linkmngr->addUrl('phpinfo','_');
				$this->addMenuItem('PHP info','phpinfo','info',1110);
			}
			
			if($_SESSION['perm']>=1111)
			{
				$this->linkmngr->addUrl('exthdd','exthdd_pi_page.html.php');
				$this->addMenuItem('Ext HDD','exthdd','cloud',1111);
				
				$this->linkmngr->addUrl('users','users_page.html.php');
				$this->addMenuItem('Users','users','users',1111);
				
				$this->linkmngr->addUrl('gpio','gpio_pi_page.html.php');
				
				$this->linkmngr->addUrl('users/user','user_profile.html.php');
				
				$this->linkmngr->addUrl('users/view','view_profile.html.php');
				
				$this->linkmngr->addUrl('shutdown','_');
			}
			//$this->linkmngr->addUrl('logout','_');
		}
		else
		{
			$this->linkmngr->addUrl('','login_page.html.php');
			$this->linkmngr->addUrl('registration','ereg_page.html.php');
			$this->linkmngr->addUrl('forgot','fgot_page.html.php');
			$this->linkmngr->addUrl('forgot/password','newpass_page.html.php');
			$this->linkmngr->addUrl('exthdd','login_page.html.php');
			$this->linkmngr->addUrl('gpio','login_page.html.php');
			$this->linkmngr->addUrl('shutdown','login_page.html.php');
		}
		$this->menu();
		$this->render();
	}
	
	/**
	 * Check sensor - getExtHDD status
	 * @return array
	 */
	final private function getExtHDDstatus()
	{
		//ExtHDD status parser
		/*if($this->is_json($this->ExtHDD))
		{*/
		$this->ExtHDD = file_get_contents('./switchexthdd', true);
		$this->ExtHDD = json_decode($this->ExtHDD,true);
		$this->ExtHDDcontent = fopen ("http://".$this->ExtHDD['ip'], "r");
		if (!$this->ExtHDDcontent) {
			exit;
		}
		$this->ExtHDDcontent = stream_get_contents($this->ExtHDDcontent);
		$this->HDDerr = 0;
		/*}
		else
		{
			$this->ExtHDDcontent = 1;
			$this->HDDerr = 1;
		}*/
		return $this->ExtHDD;
	}
	
	/**
	 * getCondensation status
	 * @return array
	 */
	final private function getCondensation()
	{
		//condensation level parser
		try
		{
			$this->Condensation = file_get_contents('./watercondensator', true);
			$this->Condensation = json_decode($this->Condensation,true);
			$this->CondensationSTS = $this->Condensation['msg'];
			$this->CondensationLVL = $this->Condensation['water'];
		}
		catch(Exception $e)
		{
			$this->err=$e->getMessage();
			$this->CondensationSTS = 'ERROR';
		}
		
		return $this->Condensation;
	}
	
	/**
	 * getTemperatures - get temperatures from pihome player
	 */
	final private function getTemperatures()
	{
		//CPU temperature
		$this->playerCPUtemperature = file_get_contents('./piplayertemperature', true);
		
		$this->CPUtemperature = file_get_contents('./temperature', true);
		$this->CPUtemperature = explode('=',$this->CPUtemperature);
		$this->CPUtemperature = substr($this->CPUtemperature[1], 0, -3);
		try
		{
			$this->playerCPUtemperature=json_decode($this->playerCPUtemperature,true);
			
			$pcjson = file_get_contents('./maincomputer', true);
			$this->pcstatusjson=json_decode($pcjson,true);
			if($this->pcstatusjson['status']==200)
			{
				$this->pcstatus=true;
			}
			else
			{
				$this->pcstatus=false;
			}
		}
		catch(Exception $e)
		{
			$e->getMessage();
			$this->pcstatus=false;
		}
	}
	
	/**
	 * Add item to main menu
	 * @param string $name - content name
	 * @param string $ling - link URL
	 * @param string $icon - icon class
	 * @param string $perm - minimal menu item permission
	 */ 
	final private function addMenuItem($name,$link,$icon,$perm)
	{
		if($_SESSION['perm']>=$perm)
		{
			$this->mainmenu[] = array(
				'name'=>$name,
				'link'=>$link,
				'icon'=>'fa fa-'.$icon,
				'perm'=>$perm
			);
		}
	}
	
	/**
	 * Rendering menu template
	 */
	final private function menu()
	{
		$logman = $this->logman;
		require_once('.'.$this->template.'/menu_block.html.php');
	}
	
	/**
	 * Rendering page template
	 */
	final private function render()
	{
		$logman = $this->logman;
		$paginator = $this->linkmngr->getPage(PAGE);
		if((file_exists('.'.$this->template.'/'.$paginator))&&($paginator!=null))
		{
			require_once('.'.$this->template.'/'.$paginator);
		}
		else
		{
			require_once('.'.$this->template.'/page_404.html.php');
		}
		
		unset($this->CondensationSTS);
		unset($this->CondensationLVL);
		unset($this->err);
		unset($this->CPUtemperature);
		unset($this->playerCPUtemperature);
		unset($this->pcstatus);
		unset($this->pcstatusjson);
	}
	
	/**
	 * Ajax
	 */
	final public function ajax()
	{
		require_once('./fw_modules/pihome/ajax_pihome.php');
	}
	
	/**
	 * JSON check
	 * 
	 * @return mix
	 */
	final private function is_json($str)
	{ 
		return json_decode($str) != null;
	}
	
	/**
	 * Generate users list
	 * 
	 * @return array
	 */
	final private function usersList()
	{
		$this->mysqlinterface->select(array(
			'users'=>array(
				'condition'=>array('`id`>=0')
			)
		));
		return $this->mysqlinterface->execute();
	}
	
	/**
	 * Generate user data list
	 * 
	 * @param integer
	 * @return array
	 */
	final private function userBasicProfile($id)
	{
		$this->mysqlinterface->select(array(
			'users'=>array(
				'condition'=>array('`id`='.($id?$id:'-1'))
			)
		));
		return $this->mysqlinterface->execute();
	}
	
	final private function profileUpdate($login)
	{
		$filtered_post = array();
		foreach($_POST as $key=>$val)
		{
			if($key!='pass')
			{
				$filtered_post[$key] = str_ireplace(array(
					"%",
					"=",
					"'",
					'"',
					"$",
					"<",
					">",
					"`"
				), array(
					"",
					"",
					"",
					"",
					"",
					"",
					"",
					""
				), $val);
			}
			else
			{
				$filtered_post[$key] = $_POST[$key];
			}
		}
		$filtered_name = explode(' ',$filtered_post['name']);
		if($this->logman->getSessionData('perm')>=1111)
		{
			$update = array(
				'email'=>$filtered_post['email'],
				'fname'=>$filtered_name[0],
				'lname'=>$filtered_name[1],
				'perm'=>$filtered_post['perm'],
				'ip'=>$_SERVER['REMOTE_ADDR'],
				'agent'=>'admin',
				'date'=>date('Y-m-d'),
				'time'=>date('H:i:s')
			);
			if($filtered_post['perm']>=1100)
			{
				if($filtered_post['perm']<1111)
				{
					$update['sign'] = 'profile_changed_by_admin';
				}
				$update['active'] = $filtered_post['active'];
			}
			else
			{
				$update['sign'] = 'banned';
				$update['active'] = '-1';
			}
			if($filtered_post['password']!='')
			{
				$updatespecial['pass'] = "MD5({$filtered_post['password']})";
			}
			$this->mysqlinterface->update(array('users'=>array(
				'condition'=>array(
					'`id`='.$_GET['u'],
					'`login`="'.$login.'"'
				),
				'set'=>$update,
				'set()'=>$updatespecial
			)));
		}
		else
		{
			$this->mysqlinterface->update(array('users'=>array(
				'condition'=>array(
					'`login`='.$login,
					'`perm`>=1000',
					'`sign`!="kicked"',
					'`sign`!="banned"',
					'`active`>=1'
				),
				'set'=>array(
					'pass'=>md5($filtered_post['password']),
					'email'=>$filtered_post['email'],
					'fname'=>$filtered_name[0],
					'lname'=>$filtered_name[1],
					'ip'=>$_SERVER['REMOTE_ADDR'],
					'date'=>date('Y-m-d'),
					'time'=>date('H:i:s')
				)
			)));
		}
		$this->mysqlinterface->execute();
	}
	
	/**
	 * Remove insecured data
	 * 
	 * @param key string
	 * @return string
	 */
	final public function profile($key)
	{
		$data = array();
		$data = $this->userBasicProfile($_GET['u']);
		unset($data['pass']);
		unset($data['id']);
		unset($data['sign']);
		return $data[0][$key];
	}
	
	/**
	 * Generate users list table
	 * 
	 * structure:
	 * 		tr
	 * 			td = id
	 * 			td = login
	 * 			td = active
	 * 			td = user type
	 * 			td = time
	 * 			td = action
	 * 
	 * @return string
	 */
	final public function usersLines()
	{
		$full = $this->usersList();
		foreach($full as $v)
		{
			if($v['perm']<1111)
			{
				switch($v['sign'])
				{
					case 'kicked':
						$noadmin = " | <a href='#ban-{$v['id']}' class='ban-user' data-user='{$v['id']}'>Ban</a> | <a href='#remove-{$v['id']}' class='remove-user' data-user='{$v['id']}'>Remove</a>";
					break;
					case 'banned':
						$noadmin = " | <a href='#remove-{$v['id']}' class='remove-user' data-user='{$v['id']}'>Remove</a>";
					break;
					default:
						$noadmin = " | <a href='#kick-{$v['id']}' class='kill-session' data-user='{$v['id']}'>Kick</a> | <a href='#ban-{$v['id']}' class='ban-user' data-user='{$v['id']}'>Ban</a> | <a href='#remove-{$v['id']}' class='remove-user' data-user='{$v['id']}'>Remove</a>";
					break;
				}
			}
			else
			{
				$noadmin = "";
			}
			$table .= "<tr class='even pointer'>
                        <td class=''>{$v['id']}</td>
                        <td class=''>{$v['login']}</td>
                        <td class=''>{$v['active']}</td>
                        <td class=''>".($v['sign']=='kicked'?'kicked '.$this->permDecode($v['perm']):$this->permDecode($v['perm']))."</td>
                        <td class=''>{$v['date']} {$v['time']}</td>
                        <td class='last'><a href='./users/view?u={$v['id']}'>View</a> | <a href='./users/user?u={$v['id']}'>Edit</a>{$noadmin}</td>
                      </tr>";
		}
		return $table;
	}
}
?>
