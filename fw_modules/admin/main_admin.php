<?php
class admin extends construct
{
	/**
	 * seoGenerator - create all data for meta tag
	 */
	final private function seoGenerator()
	{
		$this->mysqlinterface->select(array(
			'virtual_seo_config'=>array(
				'condition'=>array(
					'(`module`="admin") AND ((`url`="'.PAGE.'") OR (`url`="%") OR (`url`="*"))',
				)
			)
		));
		$meta = $this->mysqlinterface->execute();
		
		foreach($meta as $key=>$val)
		{
			$metahtml[$val['meta']]=$val['content'];
		}
		
		$this->seo->encode();
		$this->seo->title($metahtml['title']);
		$this->seo->owner($metahtml['owner']);
		$this->seo->custom("<script>var server_name='{$this->server_url}';</script>");
		$this->seo->custom("<meta http-equiv='X-UA-Compatible' content='{$metahtml['X-UA-Compatible']}'>");
		$this->seo->custom("<meta http-equiv='cache-control' content='{$metahtml['cache-control']}'>");
		$this->seo->custom("<meta http-equiv='expires' content='{$metahtml['expires']}'>");
		$this->seo->custom("<meta http-equiv='pragma' content='{$metahtml['pragma']}'>");
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
				$mask = $this->logman->getSessionData('userid');
				foreach(glob("./fw_media/gdpr/{$mask}_*.json") as $filename)
				{
					unlink($filename);
				}
				header('Location: '.$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.(($this->url!='')?'/':''));
			break;
			case 'users/user/save':
			case 'profile/save':
				$status = false;
				if($_SESSION['perm']>=1111)
				{
					$this->profileUpdate($this->profile('login'));
					$status = true;
					header('Location: '.$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.(($this->url!='')?'/':'').'users/view?u='.$_GET['u']);
				}
				else
				{
					$this->profileUpdate($this->logman->getSessionData('login'));
					$status = true;
					header('Location: '.$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.(($this->url!='')?'/':'').'profile');
				}
			break;
			case $this->menuUrlEdit('url').'/save':
				if($_SESSION['perm']>=1111)
				{
					$this->menuUpdate();
					header('Location: '.$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.(($this->url!='')?'/':'').'menu-items');
				}
			break;
			case $this->urlEdit('url','banner_edit.html.php').'/save':
				if($_SESSION['perm']>=1111)
				{
					unset($_POST['article']);
					foreach($_POST as $key=>$val)
					{
						foreach($val as $keyB=>$valB)
						{
							if($valB)
							{
								$postJson[$keyB][$key] = $valB;
							}
						}
					}
					$_POST['article'] = json_encode($postJson);
				}
			case $this->urlEdit('url','page_edit.html.php').'/save':
				if($_SESSION['perm']>=1111)
				{
					$this->pageUpdate();
					if($_POST['newsletter']!='true')
					{
						header('Location: '.$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.(($this->url!='')?'/':'').'pages');
					}
					else
					{
						$this->newsletterToSend($_GET['i']);
						header('Location: '.$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.(($this->url!='')?'/':'').'newsletter');
					}
				}
			break;
			case str_ireplace('/edit','',$this->urlEdit('url','newsletter_table.html.php')).'/cancel':
				if($_SESSION['perm']>=1111)
				{
					$this->cancelNewsletter($_GET['i']);
					header('Location: '.$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.(($this->url!='')?'/':'').str_ireplace('/edit','',$this->urlEdit('url','newsletter_table.html.php')));
				}
			break;
			case str_ireplace('/edit','',$this->urlEdit('url','newsletter_table.html.php')).'/send':
				if($_SESSION['perm']>=1110)
				{
					$this->sendNewsletter();
					header('Location: '.$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.(($this->url!='')?'/':'').$this->urlEdit('url','newsletter_table.html.php').'/success');
				}
			break;
			case $this->urlEdit('url','perm_edit.html.php').'/save':
				if($_SESSION['perm']>=1111)
				{
					$this->permUpdate();
					header('Location: '.$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.(($this->url!='')?'/':'').'perm');
				}
			break;
		}
	}
	
	/**
	 * Send newsletter message for all recipients
	 */
	final private function sendNewsletter()
	{
		if($this->logman->getSessionData('perm')>=1110)
		{
			$this->mysqlinterface->select(array(
				'newsletter'=>array(
					'condition'=>array(
						'`newsletter_id`="'.$_GET['i'].'"',
						'`status`=0'
					),
					'sort'=>array(
						'desc'=>'`id`',
					)
				)
			));
			$newsletter = $this->mysqlinterface->execute();
			$this->mysqlinterface->select(array(
				'articles'=>array(
					'condition'=>array(
						'`id`="'.$newsletter[0]['newsletter_id'].'"',
						'`perm`<=1000'
					)
				)
			));
			$data = $this->mysqlinterface->execute();
			$this->mysqlinterface->update(array(
				'newsletter'=>array(
					'condition'=>array(
						'`newsletter_id`="'.$newsletter[0]['newsletter_id'].'"',
					),
					'set'=>array(
						'status'=>1
					)
				)
			));
			$this->mysqlinterface->execute();
			$email=$this->newsletterRecipientsList();
			foreach($email as $key=>$val)
			{
				$emailList[]=$val['email'];
			}
			
			if($data[0]['article']!='')
			{
				$this->email->to(implode(', ',$emailList));
				$this->email->from('info@'.$_SERVER['SERVER_NAME']);
				$this->email->subject($data[0]['article_sumary'].' - '.$_SERVER['SERVER_NAME']);
				$this->email->html();
				$this->emailhead->encode();
				$this->email->body($this->emailhead->save().'<body>'.preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$data[0]['article']).'</body></html>');
				$this->email->send();
			}
			else
			{
				var_dump($_GET).PHP_EOL;
				var_dump($newsletter).PHP_EOL;
				var_dump($data);
				die;
			}
		}
	}
	
	/**
	 * Generate newsletter list table
	 * 
	 * @param $status int
	 * @return string
	 */
	final private function newsletterLines()
	{
		$this->mysqlinterface->select(array(
			'newsletter'=>array(
				'condition'=>array(
					'`id`>0',
					'`status`=0'
				),
				'sort'=>array(
					'desc'=>'`id`',
				)
			)
		));
		$newsletter = $this->mysqlinterface->execute();
		foreach($newsletter as $v)
		{
			$condition[]='`id`='.$v['newsletter_id'];
		}
		$cond = '('.implode(' OR ',$condition).')';
		if(sizeof($condition))
		{
			$this->mysqlinterface->select(array(
				'articles'=>array(
					'condition'=>array(
						$cond,
						'`perm`<=1000'
					),
					'sort'=>array(
						'desc'=>'`id`',
					)
				)
			));
			$pages = $this->mysqlinterface->execute();
			$editUrl = $this->urlEdit('url','page_edit.html.php');
			$url = str_ireplace('/edit','',$this->urlEdit('url','newsletter_table.html.php'));
			foreach($pages as $v)
			{
				$cancelUrl =  $url.'/cancel';
				$sendUrl =  $url.'/send';
				$switch  = "<a href='{$sendUrl}?i={$v['id']}#send-newsletter-{$v['id']}' class='send-newsletter btn btn-primary btn-xs' data-send='{$v['id']}'>Send</a>";
				$switch .= "<a href='{$editUrl}?i={$v['id']}&tinymce=1&newsleter=1#edit-newsletter-{$v['id']}' class='edit-newsletter btn btn-success btn-xs' data-edit='{$v['id']}'>Edit</a>";
				$switch .= "<a href='{$cancelUrl}?i={$v['id']}#cancel-newsletter-{$v['id']}' class='cancel-newsletter btn btn-default btn-xs' data-cancel='{$v['id']}'>Cancel</a>";
				$table .= "<tr class='even pointer'>
							<td class=''>{$v['id']}</td>
							<td class=''>{$v['name']}</td>
							<td class=''>{$v['article_sumary']}</td>
							<td class=''>{$v['date']}</td>
							<td class=''>{$v['time']}</td>
							<td class=''>{$switch}</td>
						  </tr>".PHP_EOL;
			}
		}
		return $table;
	}
	
	/**
	 * Generate newsletter recipients array table
	 * 
	 * @return array
	 */
	final private function newsletterRecipientsList()
	{
		$this->mysqlinterface->select(array(
			'newsletter_recipients'=>array(
				'condition'=>array(
					'`id`>0',
					'`email`!=""',
					'`approval`=1',
					'`approval_from`<=NOW()',
					'`approval_to`>=NOW()'
				),
				'sort'=>array(
					'desc'=>'`id`',
				)
			)
		));
		return $this->mysqlinterface->execute();
	}
	
	/**
	 * Generate newsletter recipients list table
	 * 
	 * @return string
	 */
	final private function newsletterRecipientsLines()
	{
		$newsletter = $this->newsletterRecipientsList();
		if(sizeof($newsletter)>0)
		{
			foreach($newsletter as $v)
			{
				$table .= "<tr class='even pointer'>
							<td class=''>{$v['id']}</td>
							<td class=''>{$v['email']}</td>
							<td class=''>{$v['approval_from']}</td>
							<td class=''>{$v['approval_to']}</td>
						  </tr>".PHP_EOL;
			}
		}
		return $table;
	}
	
	/**
	 * Insert to newsletter table as ready to send
	*/
	final private function newsletterToSend($id)
	{
		$this->mysqlinterface->insert(array(
		'newsletter'=>array(
			'id'=>'',
			'newsletter_id'=>$id,
			'status'=>0
		)));
		$this->mysqlinterface->execute();
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
					'((`view_parameter`='.$viewparam.') OR (`view_parameter`=3))'
				),
				'sort'=>array(
					'asc'=>'`sort`'
				)
			)
		));
		
		$menu = $this->mysqlinterface->execute();
		
		return $menu;
	}
	
	final private function cancelNewsletter($nid)
	{
		if($_SESSION['perm']>=1110)
		{
			$this->mysqlinterface->update(array('newsletter'=>array(
				'condition'=>array(
					'`id`>=0',
					'`newsletter_id`='.$nid,
					'`status`!=-1'
				),
				'set'=>array(
					'status'=>-1,
				)
			)));
			$this->mysqlinterface->execute();
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Main logic
	 */ 
	final protected function mainLogic()
	{
		$this->page();
		
		$cfg = $this->readVSC('admin');
		$this->defaultcfg = $cfg;
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
				$this->addMenuItem($val['item'],$val['url'],$val['icon'],$val['perm'],$val['parent_id'],$val['id']);
			}
		}
		$this->render();
	}
	
	/**
	 * Add item to main menu
	 * @param string $name - content name
	 * @param string $ling - link URL
	 * @param string $icon - icon class
	 * @param string $perm - minimal menu item permission
	 */ 
	final private function addMenuItem($name,$link,$icon,$perm,$parent=false,$id=false)
	{
		$sessionPerm = (int)$_SESSION['perm'];
		$sessionPerm = ($sessionPerm>0?$sessionPerm:0);
		if((int)$sessionPerm>=(int)$perm)
		{
			$this->mainmenu[] = array(
				'id'=>$id,
				'name'=>$name,
				'link'=>str_ireplace('//','/',$link),
				'icon'=>'fa fa-'.$icon,
				'perm'=>$perm,
				'parent'=>$parent
			);
		}
	}
	
	/**
	 * Rendering menu template
	 */
	final private function menu()
	{
		$logman = $this->logman;
		if(file_exists('.'.$this->template.'/menu_block.html.php'))
		{
			require_once('.'.$this->template.'/menu_block.html.php');
		}
		elseif(file_exists('.'.$this->template.'/menu.html.php'))
		{
			require_once('.'.$this->template.'/menu.html.php');
		}
		else
		{
			die('ERROR: TEMPLATE can not find menu file in '.$this->template.'!');
		}
	}
	
	/**
	 * Ajax
	 */
	final public function ajax()
	{
		require_once('./fw_modules/admin/ajax_admin.php');
		return $status;
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
				$switch = ($v['switch']==1?"<a href='#disable-{$v['id']}' data-lib='{$v['id']}' class='btn btn-danger btn-xs disable-lib'>Disable</a>":"<a href='#disable-{$v['id']}' data-lib='{$v['id']}' class='btn btn-success btn-xs enable-lib'>Enable</a>");
			}
			else
			{
				$switch = '<span class="btn btn-info btn-xs disabled">Locked</span>';
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
				$switch = ($v['switch']==1?"<a href='#disable-{$v['id']}' data-mod='{$v['id']}' class='btn btn-danger btn-xs disable-mod'>Disable</a>":"<a href='#enable-{$v['id']}' data-mod='{$v['id']}' class='btn btn-success btn-xs enable-mod'>Enable</a>");
			}
			else
			{
				$switch = '<span class="btn btn-info btn-xs disabled">Locked</span>';
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
	 * Generate menu list
	 * 
	 * @return array
	 */
	final private function menuList()
	{
		$this->mysqlinterface->select(array(
			'menu'=>array(
				'condition'=>array('`id`>=0')
			),
			'sort'=>array(
				'asc'=>'`sort`'
			)
		));
		return $this->mysqlinterface->execute();
	}
	/**
	 * Generate menu list (filled item name only)
	 * 
	 * @return array
	 */
	final private function menuListFilledName()
	{
		$this->mysqlinterface->select(array(
			'menu'=>array(
				'condition'=>array(
					'`id`>=0',
					'`item`!=""'
				)
			),
			'sort'=>array(
				'asc'=>'`sort`'
			)
		));
		return $this->mysqlinterface->execute();
	}
	
	/**
	 * Generate edit url for menu
	 * 
	 * @return array
	 */
	final private function menuUrlEdit($key)
	{
		$this->mysqlinterface->select(array(
			'menu'=>array(
				'condition'=>array('`template_file`="menu_edit.html.php"')
			)
		));
		$out = $this->mysqlinterface->execute();
		return $out[0][$key];
	}
	
	/**
	 * Generate pages list
	 * 
	 * @return array
	 */
	final private function pagesList()
	{
		$this->mysqlinterface->select(array(
			'articles'=>array(
				'condition'=>array(
					'`id`>=0'
				),
				'sort'=>array(
					'asc'=>'`sort`'
				)
			)
		));
		return $this->mysqlinterface->execute();
	}
	
	/*
	* Get page content by actual URL
	*/
	final public function getPageContentByUrl()
	{
		$perm = $this->logman->getSessionData('perm');
		/*var_dump($perm);
		die;*/
		if($perm>=1000)
		{
			$view='`view_parameter`>=2';
		}
		else
		{
			$view='(`view_parameter`=1 OR `view_parameter`=3)';
		}
		$this->mysqlinterface->select(array(
			'menu'=>array(
				'condition'=>array(
					'`url`="'.PAGE.'"',
					'`perm`<='.((((int)$perm)>0)?((int)$perm):0),
					$view
				)
			)
		));
		/*echo $this->mysqlinterface->debug();
		die;*/
		$menu = $this->mysqlinterface->execute();
		/*var_dump(((((int)$perm)>=0)?((int)$perm):0));
		var_dump($view);
		die;*/
		foreach($menu as $key=>$val)
		{
			$menu_url_id[]='`url_id`='.$val['id'];
		}
		$menuSQL = implode(' OR ',$menu_url_id);
		$this->mysqlinterface->select(array(
			'articles'=>array(
				'condition'=>array(
					$menuSQL,
					'`perm`<='.((((int)$perm)>0)?((int)$perm):0)
				),
				'sort'=>array(
					'asc'=>'`sort`'
				)
			)
		));
		$page_data = $this->mysqlinterface->execute();
		return $page_data;
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
	
	/**
	 * Update profile
	 */
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
				$updatespecial['pass'] = "MD5('{$filtered_post['password']}')";
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
	 * Update menu
	 */
	final private function menuUpdate()
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
		if($this->logman->getSessionData('perm')>=1111)
		{
			$update = array(
				'item'=>$filtered_post['name'],
				'icon'=>$filtered_post['icon'],
				'module'=>$filtered_post['module'],
				'template_file'=>$filtered_post['template'],
				'sort'=>$filtered_post['sort'],
				'url'=>$filtered_post['url'],
				'perm'=>$filtered_post['permission'],
				'view_parameter'=>$filtered_post['area'],
				'parent_id'=>$filtered_post['parent']
			);
			$this->mysqlinterface->update(array('menu'=>array(
				'condition'=>array(
					'`id`='.$_GET['i']
				),
				'set'=>$update
			)));
			$this->mysqlinterface->execute();
		}
	}
	
	/**
	 * Update pages
	 */
	final private function pageUpdate()
	{
		$filtered_post = $_POST;
		
		if($this->logman->getSessionData('perm')>=1111)
		{
			$update = array(
				'url_id'=>$filtered_post['url_id'],
				/*'category'=>$filtered_post['category'],
				'tag'=>$filtered_post['tag'],*/
				'name'=>$filtered_post['name'],
				'article_sumary'=>$filtered_post['summary'],
				'article'=>$filtered_post['article'],
				'date'=>date('Y-m-d'),
				'time'=>date('H:i:s'),
				'author_user_id'=>$this->logman->getSessionData('userid'),
				'perm'=>$filtered_post['perm'],
				'sort'=>$filtered_post['sort']
			);
			$this->mysqlinterface->update(array('articles'=>array(
				'condition'=>array(
					'`id`='.$_GET['i']
				),
				'set'=>$update
			)));
			$this->mysqlinterface->execute();
		}
	}
	
	/**
	 * Update perm
	 */
	final private function permUpdate()
	{
		$filtered_post = $_POST;
		
		if($this->logman->getSessionData('perm')>=1111)
		{
			$update = array(
				'perm'=>$filtered_post['code'],
				'usertype'=>$filtered_post['usertype'],
			);
			$this->mysqlinterface->update(array('perm_list'=>array(
				'condition'=>array(
					'`id`='.$_GET['i']
				),
				'set'=>$update
			)));
			$this->mysqlinterface->execute();
		}
	}
	
	/**
	 * Get page info onload from db to RAM
	 * 
	 * @return array
	 */
	final private function page()
	{
		$this->mysqlinterface->select(array(
			'articles'=>array(
				'condition'=>array('`id`='.(isset($_GET['i'])?"'".$_GET['i']."'":0))
			)
		));
		$this->page = $this->mysqlinterface->execute();
	}
	
	/**
	 * Get page info from RAM
	 * 
	 * @param $key string
	 * @param $keyno integer
	 *
	 * @return array
	 */
	final private function getPageContent($key,$keyno)
	{
		return $this->page[$keyno][$key];
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
						$noadmin = "<a href='#ban-{$v['id']}' class='ban-user btn btn-danger btn-xs' data-user='{$v['id']}'>Ban</a><a href='#remove-{$v['id']}' class='remove-user btn btn-danger btn-xs' data-user='{$v['id']}'>Remove</a>";
					break;
					case 'banned':
						$noadmin = "<a href='#remove-{$v['id']}' class='remove-user btn btn-danger btn-xs' data-user='{$v['id']}'>Remove</a>";
					break;
					default:
						$noadmin = "<a href='#kick-{$v['id']}' class='kill-session btn btn-info btn-xs' data-user='{$v['id']}'>Kick</a><a href='#ban-{$v['id']}' class='ban-user btn btn-danger btn-xs' data-user='{$v['id']}'>Ban</a><a href='#remove-{$v['id']}' class='remove-user btn btn-danger btn-xs' data-user='{$v['id']}'>Remove</a>";
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
                        <td class='last'><a href='./users/view?u={$v['id']}' class='btn btn-primary btn-xs'>View</a><a href='./users/user?u={$v['id']}' class='btn btn-success btn-xs'>Edit</a>{$noadmin}</td>
                      </tr>";
		}
		return $table;
	}
	
	/**
	 * Generate menu data list
	 * 
	 * @param integer
	 * @return array
	 */
	final private function menuBasicItem($id)
	{
		$this->mysqlinterface->select(array(
			'menu'=>array(
				'condition'=>array('`id`='.($id?$id:'-1')),
				'sort'=>array(
					'asc'=>'`sort`'
				)
			)
		));
		return $this->mysqlinterface->execute();
	}
	
	/**
	 * Remove insecured data
	 * 
	 * @param key string
	 * @return string
	 */
	final public function menuItem($key)
	{
		$data = array();
		$data = $this->menuBasicItem($_GET['i']);
		return $data[0][$key];
	}
	
	/**
	 * Generate users list table
	 * 
	 * @return string
	 */
	final public function menuLines()
	{
		if(($_GET['filter']==true)&&($_GET['item']=='filled'))
		{
			$full = $this->menuListFilledName();
		}
		else
		{
			$full = $this->menuList();
		}
		$editurl = $this->menuUrlEdit('url');
		foreach($full as $v)
		{
			$table .= "<tr class='even pointer'>
                        <td>{$v['id']}</td>
                        <td>{$v['item']}</td>
                        <td>{$v['icon']}</td>
                        <td>{$v['module']}</td>
                        <td>{$v['template_file']}</td>
						<td>{$v['sort']}</td>
						<td>{$v['url']}</td>
						<td>{$v['perm']}</td>
						<td>{$v['view_parameter']}</td>
						<td>{$v['parent_id']}</td>
						<td><a href='./{$editurl}?i={$v['id']}' data-id='{$v['id']}' class='btn btn-success btn-xs'>Edit</a><a href='#remove-menu-item-{$v['id']}' class='remove-menu-item btn btn-danger btn-xs' data-id='{$v['id']}'>Remove</a></td>
                      </tr>";
		}
		return $table;
	}
	
	/**
	 * Get perm info
	 * 
	 * @param key string
	 * @return string
	 */
	final public function groupItem($key)
	{
		$data = array();
		$id = $_GET['i'];
		$this->mysqlinterface->select(array(
			'perm_list'=>array(
				'condition'=>array('`id`='.($id?$id:'-1')),
				'sort'=>array(
					'asc'=>'`id`'
				)
			)
		));
		$data = $this->mysqlinterface->execute();
		return $data[0][$key];
	}
	
	/**
	 * Generate users list table
	 * 
	 * @return string
	 */
	final public function pagesLines()
	{
		$full = $this->pagesList();
		//$editurl = $this->menuUrlEdit('url');   --- edit for pages
		
		foreach($full as $v)
		{
			$editUrl = $this->urlEdit('url',$v['category'].'_edit.html.php');
			
			$table .= "<tr class='even pointer'>
                        <td>{$v['id']}</td>
                        <td>{$v['name']}</td>
                        <td>{$v['article_sumary']}</td>
                        <td>{$v['date']}</td>
                        <td>{$v['time']}</td>
						<td>{$v['perm']}</td>
						<td>{$v['author_user_id']}</td>
						<td><a href='./{$editUrl}?i={$v['id']}' data-id='{$v['id']}' class='btn btn-success btn-xs'>Edit</a><a href='#remove-page-{$v['id']}' class='remove-page btn btn-danger btn-xs' data-id='{$v['id']}'>Remove</a></td>
                      </tr>";
		}
		return $table;
	}
	
	/**
	 * Generate perm list table
	 * 
	 * @return string
	 */
	final private function permLines()
	{
		$this->mysqlinterface->select(array(
			'perm_list'=>array(
				'condition'=>array('`id`>=0')
			),
			'sort'=>array(
				'asc'=>'`perm`'
			)
		));
		$full = $this->mysqlinterface->execute();
		
		foreach($full as $v)
		{
			$editUrl =  $this->urlEdit('url','perm_edit.html.php');
			
			$table .= "<tr class='even pointer'>
                        <td>{$v['id']}</td>
                        <td>{$v['perm']}</td>
                        <td>{$v['usertype']}</td>
                        <td><a href='./{$editUrl}?i={$v['id']}' data-id='{$v['id']}' class='btn btn-success btn-xs'>Edit</a><a href='#remove-perm-{$v['id']}' class='remove-perm btn btn-danger btn-xs' data-id='{$v['id']}'>Remove</a></td>
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
		//$this->linkmngr->getPage(PAGE);
		$this->mysqlinterface->select(array(
			'menu'=>array(
				'condition'=>array(
					'`url`="'.PAGE.'"',
					'`perm`<="'.$logman->getSessionData('perm').'"',
					'`view_parameter`>='.($logman->checkSession()?2:1)
				),
				'sort'=>array(
					'asc'=>'`sort`'
				)
			)
		));
		
		$out = $this->mysqlinterface->execute();
		//var_dump($out);
		
		$sys404=true;
		foreach($out as $key=>$val)
		{
			$sys404=false;
			$paginator = $val['template_file'];
			$module = $val['module'];
			$cfg = $this->readVSC($module);
			$this->template = $cfg[2];
			
			if(((file_exists('.'.$this->template.'/menu_block.html.php'))||(file_exists('.'.$this->template.'/menu.html.php')))&&(('.'.$this->template.'/'.$paginator)!='./'))
			{
				$this->config_mod($this->protocol,$this->url,$this->template);
				$this->menu();
			}
			else
			{
				$this->template = $this->defaultcfg[2];
				$this->config_mod($this->protocol,$this->url,$this->template);
				$this->menu();
			}
			
			$this->template = $cfg[2];
			if((file_exists('.'.$this->template.'/'.$paginator))&&(('.'.$this->template.'/'.$paginator)!='./'))
			{
				//die('test');
				$this->config_mod($this->protocol,$this->url,$this->template);
				require_once('.'.$this->template.'/'.$paginator);
			}
		}
		if($sys404)
		{
			$this->render404();
		}
		//die('.'.$this->template.'/footer/footer.html.php');
		require_once('.'.$this->template.'/footer/footer.html.php');
	}
	
	final private function render404()
	{
		$cfg = $this->readVSC('admin');
		$this->template = $cfg[2];
		$responseCode = http_response_code();
		if(($responseCode>=0)&&($responseCode<299))
		{
			http_response_code(404);
			$this->error404Log();
		}
		require_once('.'.$this->template.'/page_404.html.php');
	}
	
	/**
	 * Console error log
	 */
	final private function error404Log()
	{
		$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$this->mysqlinterface->insert(array(
		'console'=>array(
			'id'=>'',
			'time'=>date('Y-m-d H:i:s'),
			'title'=>http_response_code()." {$url}",
			'data'=>json_encode(array(
				'url'=>$url,
				'session_data'=>$_SESSION,
				'server_data'=>$_SERVER,
				'post'=>$_POST,
				'get'=>$_GET
			))
		)));
		$this->mysqlinterface->execute();
	}
	
	final private function msgLog($mod,$msg)
	{
		$this->mysqlinterface->insert(array(
		'waiting_msg'=>array(
			'id'=>'',
			'module'=>$mod,
			'message'=>$msg,
			'status'=>0,
			'datetime'=>date('Y-m-d H:i:s'),
		)));
		$this->mysqlinterface->execute();
	}
	
	final private function readMsgLog()
	{
		$this->mysqlinterface->filter(array(
			'waiting_msg'=>array(
				'COUNT(`status`) AS `count`',
			)
		),true);
		$this->mysqlinterface->select(array(
			'waiting_msg'=>array(
				'condition'=>array(
					'`status`=0'
				)
			)
		));
		$out['ctr'] = $this->mysqlinterface->execute();
		
		$this->mysqlinterface->filter(array(
			'waiting_msg'=>array(
				'`id`,`module`,`message`,`status`,`datetime`',
			)
		),true);
		$this->mysqlinterface->select(array(
			'waiting_msg'=>array(
				'condition'=>array(
					'`status`=0'
				),
				'sort'=>array(
					'asc'=>'`id`'
				)
			)
		));
		
		$out['data'] = $this->mysqlinterface->execute();
		return $out;
	}
	
	final private function setMsgAsRead($email)
	{
		$this->mysqlinterface->update(array(
			'waiting_msg'=>array(
				'condition'=>array(
					'`module` LIKE "%msg_for_admin"',
					'`message` LIKE "%'.$email.'%"',
					'`status`=0',
				),
				'set'=>array(
					'status'=>'1'
				)
			)
		));
		$this->mysqlinterface->execute();
	}
	
	/*public function __destruct()
	{
		$this->render();
	}*/
}
?>
