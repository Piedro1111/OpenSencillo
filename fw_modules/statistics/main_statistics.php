<?php
class statistics extends construct
{
	private $cfg;
	
	final protected function mainLogic()
	{
		$this->cfg = $this->readVSC('statistics');
		$this->config_mod($this->cfg[0],$this->cfg[1],$this->cfg[2]);
	}
	final private function onEndMainLogic()
	{
		$this->cfg = $this->readVSC('statistics');
		
		$this->config_mod($this->cfg[0],$this->cfg[1],$this->cfg[2]);
		$this->mysqlinterface->insert(array(
			'statistics'=>array(
				'id'=>'',
				'url'=>'/'.PAGE,
				'referer_url'=>''.$_SERVER['HTTP_REFERER'],
				'status'=>http_response_code(),
				'remote_ip'=>$_SERVER['REMOTE_ADDR'].':'.$_SERVER['REMOTE_PORT'],
				'user_id'=>''.$this->logman->getSessionData('userid'),
				'user_agent'=>$_SERVER['HTTP_USER_AGENT'],
				'datetime'=>date('Y-m-d H:i:s')
			)
		));
		if(($_POST['save']=='log')||('log=all'==str_ireplace(' ','',$this->cfg[3])))
		{
			if(sizeof($_POST)>0)
			{
				$datehash = date('YmdHis');
				$_POST['meta_user_id'] = $this->logman->getSessionData('userid');
				$toStore=$_POST;
				unset($toStore['pass'],$toStore['password'],$toStore['password2'],$toStore['httpdata'],$toStore['datahttp']);
				foreach($toStore as $key=>$val)
				{
					$this->mysqlinterface->insert(array(
						'posted_form_data'=>array(
							'id'=>'',
							'url'=>'/'.PAGE,
							'pack_id'=>'pid_s:'.md5(PAGE) . $datehash . $this->logman->getSessionData('userid') . str_ireplace('.','',$_SERVER['REMOTE_ADDR'].$_SERVER['REMOTE_PORT']) . ':pid_e',
							'attr'=>''.$key,
							'val'=>''.$val,
							'datetime'=>date('Y-m-d H:i:s')
						)
					));
				}
				if((!($this->logman->checkSession()))&&($toStore['email']!=''))
				{
					$msg="New message from ".$toStore['email'].".";
					$this->msgLog('statistics::msg_for_admin',$msg);
				}
			}
		}
		$this->mysqlinterface->execute();
		/*if($_POST['SCLO_redirect_url']!='')
		{
			$tgt=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER[HTTP_HOST]}{$_SERVER[REQUEST_URI]}{$_POST['redirect']}";
			header("Location: ".$tgt);
		}*/
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
	
	final public function __destruct()
	{
		$this->onEndMainLogic();
	}
}
class statisticsTemplate extends construct
{
	private $cfg;
	
	final protected function mainLogic()
	{
		$this->cfg = $this->readVSC('statistics');
		$this->config_mod($this->cfg[0],$this->cfg[1],$this->cfg[2]);
	}
	final public function packEmailNo($code)
	{
		$this->mysqlinterface->filter(array(
			'posted_form_data'=>array(
				'`val`',
			)
		),true);
		$this->mysqlinterface->select(array(
			'posted_form_data'=>array(
				'condition'=>array(
					'`url`!="/"',
					'(`attr`="email" OR `attr`="e-mail" OR `attr`="mail")',
					'`pack_id`="'.$code.'"'
				),
				'sort'=>array(
					'asc'=>'`id`'
				)
			)
		));
		$email = $this->mysqlinterface->execute();
		
		$this->mysqlinterface->filter(array(
			'posted_form_data'=>array(
				'COUNT(`val`) AS `email_no`',
			)
		),true);
		$this->mysqlinterface->select(array(
			'posted_form_data'=>array(
				'condition'=>array(
					'`url`!="/"',
					'(`attr`="email" OR `attr`="e-mail" OR `attr`="mail")',
					'`val`="'.$email[0]['val'].'"'
				),
				'sort'=>array(
					'asc'=>'`id`'
				)
			)
		));
		$full = $this->mysqlinterface->execute();
		
		return array('email_no'=>$full[0]['email_no'],'email'=>$email[0]['val']);
	}
	final public function packData($code)
	{
		$this->mysqlinterface->select(array(
			'posted_form_data'=>array(
				'condition'=>array(
					'`pack_id`="'.$code.'"'
				),
				'sort'=>array(
					'asc'=>'`id`'
				)
			)
		));
		$full = $this->mysqlinterface->execute();
		
		return $full;
	}
	final public function noSystemFormPackList()
	{
		$this->mysqlinterface->filter(array(
			'posted_form_data'=>array(
				'`pack_id` AS `code`,`url`,`val`,`datetime`',
			)
		),true);
		$this->mysqlinterface->select(array(
			'posted_form_data'=>array(
				'condition'=>array(
					'`url`!="/"',
					'(`attr`="email" OR `attr`="e-mail" OR `attr`="mail")',
					'`val`!=""',
				),
				'sort'=>array(
					'asc'=>'`id`'
				)
			)
		));
		$full = $this->mysqlinterface->execute();
		
		$i=0;
		foreach($full as $v)
		{
			$editUrl =  $this->urlEdit('url','post_view.html.php');
			$i++;
			
			$table .= "<tr class='even pointer'>
                        <td>{$i}</td>
                        <td>{$v['url']}</td>
                        <td><a href='mailto:{$v['val']}'>{$v['val']}</a></td>
						<td>{$v['datetime']}</td>
                        <td><a href='./{$editUrl}?i={$v['code']}' data-id='{$v['code']}' class='btn btn-primary btn-xs'>View</a><!-- | <a href='#remove-perm-{$v['code']}' class='remove-perm' data-id='{$v['code']}'>Remove</a>--></td>
                      </tr>";
		}
		return $table;
	}
	final public function formPackList()
	{
		$this->mysqlinterface->filter(array(
			'posted_form_data'=>array(
				'`pack_id` AS `code`,`url`,`val`,`datetime`',
			)
		),true);
		$this->mysqlinterface->select(array(
			'posted_form_data'=>array(
				'condition'=>array(
					'`attr`="email"',
					'or'=>'`attr`="e-mail"',
					'or'=>'`attr`="mail"',
				),
				'sort'=>array(
					'asc'=>'`id`'
				)
			)
		));
		$full = $this->mysqlinterface->execute();
		
		$i=0;
		foreach($full as $v)
		{
			$editUrl =  $this->urlEdit('url','post_view.html.php');
			$i++;
			
			$table .= "<tr class='even pointer'>
                        <td>{$i}</td>
                        <td>{$v['url']}</td>
                        <td>{$v['val']}</td>
						<td>{$v['datetime']}</td>
                        <td><a href='./{$editUrl}?i={$v['code']}' data-id='{$v['code']}' class='btn btn-primary btn-xs'>View</a><!-- | <a href='#remove-perm-{$v['code']}' class='remove-perm' data-id='{$v['code']}'>Remove</a>--></td>
                      </tr>";
		}
		return $table;
	}
	
	final public function allPackList()
	{
		$this->mysqlinterface->filter(array(
			'posted_form_data'=>array(
				'DISTINCT `pack_id` AS `code`,`url`,`datetime`',
			)
		),true);
		$this->mysqlinterface->select(array(
			'posted_form_data'=>array(
				'condition'=>array(
					'`pack_id` LIKE "pid_s:%"'
				),
				'sort'=>array(
					'desc'=>'`id`'
				)
			)
		));
		$full = $this->mysqlinterface->execute();
		
		$i=0;
		foreach($full as $v)
		{
			$editUrl =  $this->urlEdit('url','post_view.html.php');
			$i++;
			
			$table .= "<tr class='even pointer'>
                        <td>{$i}</td>
                        <td>{$v['url']}</td>
                        <td>{$v['code']}</td>
						<td>{$v['datetime']}</td>
                        <td><a href='./{$editUrl}?i={$v['code']}' data-id='{$v['code']}' class='btn btn-primary btn-xs'>View</a><!-- | <a href='#remove-perm-{$v['code']}' class='remove-perm' data-id='{$v['code']}'>Remove</a>--></td>
                      </tr>";
		}
		return $table;
	}
	final public function topPageList()
	{
		$this->mysqlinterface->filter(array(
			'menu'=>array(
				'DISTINCT `url`',
			)
		),true);
		$this->mysqlinterface->select(array(
			'menu'=>array(
				'condition'=>array(
					'(`module`!="admin")',
					'(`module`!="pihome")',
					'(`module`!="statistics")',
					'(`view_parameter`!=2)',
					'(`perm`!=1111)',
					'(`url`!="%w_template%")',
				),
				'sort'=>array(
					'asc'=>'`id`'
				)
			)
		));
		$url = $this->mysqlinterface->execute();
		
		/*$this->mysqlinterface->filter(array(
			'statistics'=>array(
				'DISTINCT `url`',
			)
		),true);
		$this->mysqlinterface->select(array(
			'statistics'=>array(
				'condition'=>array(
					'`status`=200',
					'`url` NOT LIKE "%w_template%"',
					
				),
				'sort'=>array(
					'asc'=>'`id`'
				)
			)
		));
		$url = $this->mysqlinterface->execute();*/
		
		$i=0;
		foreach($url as $v)
		{
			$this->mysqlinterface->filter(array(
				'statistics'=>array(
					'COUNT(`url`) AS `count`',
				)
			),true);
			$this->mysqlinterface->select(array(
				'statistics'=>array(
					'condition'=>array(
						"`url`='/{$v['url']}'",
						'`url` NOT LIKE "%w_template%"'
					),
					'sort'=>array(
						'asc'=>'`id`'
					)
				)
			));
			$count = $this->mysqlinterface->execute();
			
			$table .= "<tr class='even pointer'>
                        <td><a href='{$this->server_url}/{$v['url']}' target='_blank' class='btn-link btn-xs'>/{$v['url']}</a></td>
						<td>{$count[0][0]}</td>
                      </tr>";
		}
		return $table;
	}
	final public function dailyViews()
	{
		$this->mysqlinterface->filter(array(
			'statistics'=>array(
				'COUNT(`url`) AS `count_daily`',
			)
		),true);
		$this->mysqlinterface->select(array(
			'statistics'=>array(
				'condition'=>array(
					"`datetime`>='".date('Y-m-d 00:00:00')."'",
					'`url` NOT LIKE "%w_template%"'
				),
				'sort'=>array(
					'asc'=>'`id`'
				)
			)
		));
		$url = $this->mysqlinterface->execute();
		
		return $url[0][0];
	}
	final public function monthViews()
	{
		$this->mysqlinterface->filter(array(
			'statistics'=>array(
				'COUNT(`url`) AS `count`',
			)
		),true);
		$this->mysqlinterface->select(array(
			'statistics'=>array(
				'condition'=>array(
					"`datetime`>='".date('Y-m-01 00:00:00')."'",
					'`url` NOT LIKE "%w_template%"'
				),
				'sort'=>array(
					'asc'=>'`id`'
				)
			)
		));
		$url = $this->mysqlinterface->execute();
		
		return $url[0][0];
	}
	final public function yearViews()
	{
		$this->mysqlinterface->filter(array(
			'statistics'=>array(
				'COUNT(`url`) AS `count`',
			)
		),true);
		$this->mysqlinterface->select(array(
			'statistics'=>array(
				'condition'=>array(
					"`datetime`>='".date('Y-01-01 00:00:00')."'",
					'`url` NOT LIKE "%w_template%"'
				),
				'sort'=>array(
					'asc'=>'`id`'
				)
			)
		));
		$url = $this->mysqlinterface->execute();
		
		return $url[0][0];
	}
	final public function allViews()
	{
		$this->mysqlinterface->filter(array(
			'statistics'=>array(
				'COUNT(`url`) AS `count`',
			)
		),true);
		$this->mysqlinterface->select(array(
			'statistics'=>array(
				'condition'=>array(
					"`id`>=0",
					'`url` NOT LIKE "%w_template%"'
				),
				'sort'=>array(
					'asc'=>'`id`'
				)
			)
		));
		$url = $this->mysqlinterface->execute();
		
		return $url[0][0];
	}
}
$sts = new statistics; 
?>
