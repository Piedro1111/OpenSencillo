<?php
class construct
{
	public $url;
	public $urlprefix;
	public $port;
	public $css;
	public $js;
	public $stn;
	public $protocol;
	public $seo;
	public $linkmngr;
	public $logman;
	public $template;
	
	public $usertype;
	public $mainmenu;
	
	public $libtype;
	public $alltemplates;
	
	public $mysqlinterface;
	
	final public function __construct()
	{
		error_reporting(E_ERROR | E_PARSE);
		session_start();
		
		$_SESSION['count']++;
		
		if(PAGE!='ajax.slot.php')
		{
			$this->seo = new headerSeo;
			$this->linkmngr = new url;
		}
		$this->logman = new logMan;
		
		
		$this->mysqlinterface = new mysqlinterface;
		$this->mysqlinterface->config();
		$this->mysqlinterface->connect();
		
		//main
		if(PAGE!='ajax.slot.php')
		{
			$this->mainLogic();
		}
	}
	
	/**
	* Install basic configuration for mods
	* @param mod modname string
	* @param protocol 'http' or 'https' string
	* @param template path string
	*/
	final public function install($mod,$protocol,$template)
	{
		$mysql->mysqlinterface->insert(array(
			'virtual_system_config'=>array(
				'id'=>'',
				'module'=>$mod,
				'perm'=>0,
				'switch'=>1,
				'function'=>'mod:'.$mod,
				'command'=>$mod,
				'commander'=>0
			)
		));
		$mysql->mysqlinterface->insert(array(
			'virtual_system_config'=>array(
				'id'=>'',
				'module'=>$mod,
				'perm'=>0,
				'switch'=>1,
				'function'=>'cfg:'.$protocol.','.$mod.','.$template,
				'command'=>$mod,
				'commander'=>0
			)
		));
		$mysql->mysqlinterface->execute();
	}
	
	/**
	 * Read config data
	 * 
	 * @return array
	 */
	final public function readVSC($mod)
	{
		$this->mysqlinterface->select(array(
			'virtual_system_config'=>array(
				'condition'=>array(
					'`module`="'.$mod.'"',
					'`function` LIKE "cfg:%"',
					'`command` LIKE "config_mod:%"'
				)
			)
		));
		$cfg = $this->mysqlinterface->execute();
		$cfg = explode(':',$cfg[0]['command']);
		$cfg = explode(',',$cfg[1]);
		
		return $cfg;
	}
	
	final public function config_mod($protocol,$url,$template)
	{
		$this->protocol = $protocol; //'http'
		$this->url = $url; //'pihome'
		$this->urlprefix = $this->url;
		$this->port = ':'.$_SERVER['SERVER_PORT'];
		$this->template = $template; //'/fw_templates/additional/rpi/production'
		$this->css = $this->urlprefix.$this->template;
		$this->js = $this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.$this->template;
		$this->stn = $this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/shutdown';
	}
}
class admin extends construct
{
	/**
	 * seoGenerator - create all data for meta tag
	 */
	final private function seoGenerator()
	{
		$this->seo->encode();
		$this->seo->title("OpenSencillo | ADMIN SYSTEM");
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
	final protected function permDecode($perm=false)
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
	final protected function defaultHead($PAGE)
	{
		$filtered = explode('?',$PAGE);
		$PAGE = $filtered[0];
		switch($PAGE)
		{
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
	 * Read menu
	 * 
	 * @return array
	 */
	final private function readMenu()
	{
		if($this->logman->checkSession())
		{
			$perm = $_SESSION['perm'];
			$viewparam = 2;
		}
		else
		{
			$perm = 0;
			$viewparam = 1;
		}
		
		$this->mysqlinterface->select(array(
			'virtual_system_config'=>array(
				'condition'=>array(
					'`switch`=1',
					'`function` LIKE "mod:%"'
				)
			)
		));
		$mods = $this->mysqlinterface->execute();
		$modsctr = count($mods);
		
		foreach($mods as $val)
		{
			$condition[] = '`module`="'.$val['module'].'"';
		}
		$outcond = implode(' OR ',$condition);
		
		$this->mysqlinterface->select(array(
			'menu'=>array(
				'condition'=>array(
					'('.$outcond.')',
					'`sort`>=0',
					'`perm`<='.$perm,
					'`view_parameter`='.$viewparam
				),
				'sort'=>array(
					'asc'=>'`sort`'
				)
			)
		));
		
		$menu = $this->mysqlinterface->execute();
		
		return $menu;
	}
	
	/**
	 * Main logic
	 */ 
	final protected function mainLogic()
	{
		$cfg = $this->readVSC('admin');
		$menu = $this->readMenu();
		
		$this->config_mod($cfg[0],$cfg[1],$cfg[2]);
		$this->seoGenerator();
		$this->defaultHead(PAGE);
		$this->permDecode();
		
		foreach($menu as $val)
		{
			$this->linkmngr->addUrl($val['url'],$val['template_file']);
			if(($val['item']!='')&&($val['view_parameter']>0))
			{
				$this->addMenuItem($val['item'],$val['url'],$val['icon'],$val['perm']);
			}
		}
		
		/*if($this->logman->checkSession())
		{
			if($_SESSION['perm']>=1110)
			{
				$this->linkmngr->addUrl('phpinfo','_');
				$this->addMenuItem('PHP info','phpinfo','info',1110);
			}
			
			if($_SESSION['perm']>=1111)
			{
				$this->linkmngr->addUrl('modules','modules_page.html.php');
				$this->addMenuItem('Modules','modules','cubes',1100);
				
				$this->linkmngr->addUrl('users','users_page.html.php');
				$this->addMenuItem('Users','users','users',1111);
				
				$this->linkmngr->addUrl('users/user','user_profile.html.php');
				
				$this->linkmngr->addUrl('users/view','view_profile.html.php');
			}
		}
		else
		{
			$this->linkmngr->addUrl('','login_page.html.php');
			$this->linkmngr->addUrl('registration','ereg_page.html.php');
			$this->linkmngr->addUrl('forgot','fgot_page.html.php');
			$this->linkmngr->addUrl('forgot/password','newpass_page.html.php');
		}*/
		$this->menu();
		$this->render();
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
	 * Ajax
	 */
	final public function ajax()
	{
		require_once('./fw_modules/admin/ajax_admin.php');
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
	 * Generate libraries and mods main files list
	 * 
	 * @return array
	 */
	final private function libList($status,$type)
	{
		$this->mysqlinterface->select(array(
			'virtual_system_config'=>array(
				'condition'=>array(
					'`id`>0',
					'`function` LIKE "'.$type.':%"',
					'`switch`="'.$status.'"'
				)
			)
		));
		return $this->mysqlinterface->execute();
	}
	
	/**
	 * Generate library list table
	 * 
	 * @param $status int
	 * 
	 * structure:
	 * 		tr
	 * 			td = id
	 * 			td = sensor
	 * 			td = data
	 * 			td = date
	 * 			td = time
	 * 
	 * @return string
	 */
	final public function libLines($status)
	{
		$this->mysqlinterface->select(array(
			'virtual_system_config'=>array(
				'condition'=>array(
					'`id`>0',
					'`function`="bootup:dependency_id"',
					'`perm`>=0',
					'`switch`=1'
				),
				'limit'=>1
			)
		));
		$securedlibs = $this->mysqlinterface->execute();
		$securedlibs = explode(',',$securedlibs[0]['command']);
		
		$full = $this->libList($status,'lib');
		foreach($full as $v)
		{
			if(!(in_array($v['id'],$securedlibs)))
			{
				$switch = ($v['switch']==1?"<a href='#disable-{$v['id']}' class='disable-lib' data-lib='{$v['id']}'>Disable</a>":"<a href='#disable-{$v['id']}' class='enable-lib' data-lib='{$v['id']}'>Enable</a>");
			}
			else
			{
				$switch = 'Locked';
			}
			$table .= "<tr class='even pointer'>
                        <td class=''>{$v['id']}</td>
                        <td class=''>{$v['module']}</td>
                        <td class=''>{$v['perm']}</td>
                        <td class=''>{$switch}</td>
                      </tr>".PHP_EOL;
		}
		return $table;
	}
	
	/**
	 * Generate module list table
	 * 
	 * @param $status int
	 * 
	 * structure:
	 * 		tr
	 * 			td = id
	 * 			td = sensor
	 * 			td = data
	 * 			td = date
	 * 			td = time
	 * 
	 * @return string
	 */
	final public function modLines($status)
	{
		$this->mysqlinterface->select(array(
			'virtual_system_config'=>array(
				'condition'=>array(
					'`id`>0',
					'`function`="bootup:dependency_id"',
					'`perm`>=0',
					'`switch`=1'
				),
				'limit'=>1
			)
		));
		$securedlibs = $this->mysqlinterface->execute();
		$securedlibs = explode(',',$securedlibs[0]['command']);
		
		$full = $this->libList($status,'mod');
		
		foreach($full as $v)
		{
			if(!(in_array($v['id'],$securedlibs)))
			{
				$switch = ($v['switch']==1?"<a href='#disable-{$v['id']}' class='disable-mod' data-mod='{$v['id']}'>Disable</a>":"<a href='#enable-{$v['id']}' class='enable-mod' data-mod='{$v['id']}'>Enable</a>");
			}
			else
			{
				$switch = 'Locked';
			}
			$table .= "<tr class='even pointer'>
                        <td class=''>{$v['id']}</td>
                        <td class=''>{$v['module']}</td>
                        <td class=''>{$v['perm']}</td>
                        <td class=''>{$switch}</td>
                      </tr>".PHP_EOL;
		}
		return $table;
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
	
	/**
	 * Rendering page template
	 */
	final private function render()
	{
		$logman = $this->logman;
		$paginator = $this->linkmngr->getPage(PAGE);
		
		$this->mysqlinterface->select(array(
			'menu'=>array(
				'condition'=>array(
					'`template_file`="'.$paginator.'"',
				)
			)
		));
		
		$out = $this->mysqlinterface->execute();
		$module = $out[0]['module'];
		
		$cfg = $this->readVSC($module);
		$this->template = $cfg[2];
		
		if((file_exists('.'.$this->template.'/'.$paginator))&&(('.'.$this->template.'/'.$paginator)!='./'))
		{
			require_once('.'.$this->template.'/'.$paginator);
		}
		else
		{
			require_once('./fw_templates/additional/admin/production/page_404.html.php');
		}
	}
}
?>
