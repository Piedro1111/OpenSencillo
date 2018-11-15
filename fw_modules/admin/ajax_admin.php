<?php
$log=$this->logman->getSignedUser();
$status=array(
	'called'=>$_POST['atype'],
	'date'=>date('Y-m-d'),
	'time'=>date('H:i:s')
);

if($_POST['atype']!='')
{
	$ajax=$_POST;
}

switch($ajax['atype'])
{
	case 'login':
		if(is_null($ajax['pass']))
		{
			$ajax['pass']=$ajax['datahttp'];
		}
		$status = $this->logman->login($ajax);
		//var_dump($_POST);
		break;
	case 'ereg':
		$this->logman->openTable('users');
		if(filter_var($_POST[$ajax['atype'].'email'], FILTER_VALIDATE_EMAIL))
		{
			if($_POST[$ajax['atype'].'pass']===$_POST[$ajax['atype'].'rtp'])
			{
				$status['user']=$this->logman->output("`login`='".strtolower($ajax[$ajax['atype'].'email'])."'","`id` ASC",1);
				if(empty($status['user']['line'][1][0]))
				{
					try {
						$this->msgLog('admin::'.$ajax['atype'],'User '.$_POST[$ajax['atype'].'email'].' registered.');
						$name = explode(" ",$_POST[$ajax['atype'].'fullname']);
						$this->logman->insert("'first_use',0,'" . strtolower($_POST[$ajax['atype'].'email']) . "',MD5('" . $_POST[$ajax['atype'].'pass'] . "'),'" . strtolower($_POST[$ajax['atype'].'email']) . "','" . $this->logman->clean(ucwords(strtolower($name[0]))) . "','" . $this->logman->clean(ucwords(strtolower($name[1]))) . "',1000,'" . $log['external_ip'] . ":" . $log['port'] . "','" . $log['agent'] . "',DATE(NOW()),TIME(NOW())");
						$status['status'] = 'ok';
						$status['code'] = 200;
					} catch (Exception $e) {
						$status['status'] = 'failed';
						$status['code'] = 417;
					}
				}
				else
				{
					$status['status'] = 'exist';
					$status['code'] = 409;
				}
			}
			else
			{
				$status['status'] = 'conflict pass retype';
				$status['code'] = 409.1;
			}
		} else {
			$status['status'] = 'invalid';
			$status['code'] = 403;
		}
		break;
	case 'create::user':
		if(($_SESSION['perm']>=1111)&&($this->logman->checkSession()))
		{
			$email = date('yzHis')."@".$_SERVER['SERVER_NAME'];
			try {
				$name = explode(" ",'User User');
				
				$this->mysqlinterface->insert(array(
				'users'=>array(
					'id'=>'',
					'sign'=>'first_use',
					'active'=>0,
					'login'=>strtolower($email),
					'pass'=>'',
					'email'=>strtolower($email),
					'fname'=>'',
					'lname'=>'',
					'perm'=>1000,
					'ip'=>$log['external_ip'] . ":" . $log['port'],
					'agent'=>$log['agent'],
					'date'=>date('Y-m-d'),
					'time'=>date('H:i:s')
				)));
				$this->mysqlinterface->execute();
				
				$this->mysqlinterface->select(array(
				'users'=>array(
					'condition'=>array(
						'`login`="'.$email.'"',
						'`perm`=1000'
					),
					'sort'=>array(
						'desc'=>'`id`'
					),
					'limit'=>1
				)));
				$id = $this->mysqlinterface->execute();

				$status['unew'] = $id[0]['id'];
				$status['enew'] = $email;
				$status['status'] = 'ok';
				$status['code'] = 200;
			} catch (Exception $e) {
				$status['status'] = 'failed';
				$status['code'] = 417;
			}
		}
		else
		{
			$status['status'] = 'perm: '.$_SESSION['perm'];
			$status['code'] = 403;
		}
		break;
		case 'create::menu::item':
		if(($_SESSION['perm']>=1111)&&($this->logman->checkSession()))
		{
			try {
				$itemname = substr(md5(date('Ymdhis')),0,4);
				$this->mysqlinterface->insert(array(
				'menu'=>array(
					'id'=>'',
					'item'=>'new #'.$itemname,
					'icon'=>'',
					'module'=>'####',
					'template_file'=>'#'.$itemname,
					'sort'=>0,
					'url'=>'#',
					'perm'=>9999,
					'view_parameter'=>9,
					'parent_id'=>0
				)));
				$this->mysqlinterface->execute();
				
				$this->mysqlinterface->select(array(
				'menu'=>array(
					'condition'=>array(
						'`item`="new #'.$itemname.'"'
					),
					'sort'=>array(
						'desc'=>'`id`'
					),
					'limit'=>1
				)));
				$id = $this->mysqlinterface->execute();

				$status['mnew'] = $id[0]['id'];
				$status['inew'] = $id[0]['id'];
				$status['status'] = 'ok';
				$status['code'] = 200;
			} catch (Exception $e) {
				$status['status'] = 'failed';
				$status['code'] = 417;
			}
		}
		else
		{
			$status['status'] = 'perm: '.$_SESSION['perm'];
			$status['code'] = 403;
		}
		break;
	case 'create::perm::blank':
		if(($_SESSION['perm']>=1111)&&($this->logman->checkSession()))
		{
			try {
				$itemname = substr(md5(date('Ymdhis')),0,4);
				$this->mysqlinterface->insert(array(
				'perm_list'=>array(
					'id'=>'',
					'perm'=>0,
					'usertype'=>'#'.$itemname
				)));
				$this->mysqlinterface->execute();
				
				$this->mysqlinterface->select(array(
				'perm_list'=>array(
					'condition'=>array(
						'`usertype`="#'.$itemname.'"'
					),
					'sort'=>array(
						'desc'=>'`id`'
					),
					'limit'=>1
				)));
				$id = $this->mysqlinterface->execute();

				$status['mnew'] = $id[0]['id'];
				$status['inew'] = $id[0]['id'];
				$status['status'] = 'ok';
				$status['code'] = 200;
			} catch (Exception $e) {
				$status['status'] = 'failed';
				$status['code'] = 417;
			}
		}
		else
		{
			$status['status'] = 'perm: '.$_SESSION['perm'];
			$status['code'] = 403;
		}
		break;
	case 'create::banner::blank':
	case 'create::page::blank':
		$action = explode('::',$ajax['atype']);
		if(($_SESSION['perm']>=1111)&&($this->logman->checkSession()))
		{
			try {
				$itemname = substr(md5(date('Ymdhis')),0,4);
				$this->mysqlinterface->insert(array(
				'articles'=>array(
					'id'=>'',
					'url_id'=>0,
					'category'=>$action[1],
					'tag'=>json_encode(array($action[1],$action[2],$itemname)),
					'name'=>'Lorem Ipsum #'.$itemname,
					'article_sumary'=>$action[1],
					'article'=>'blank page',
					'date'=>date('Y-m-d'),
					'time'=>date('H:i:s'),
					'author_user_id'=>$_SESSION['userid'],
					'perm'=>9999,
					'sort'=>0
				)));
				$this->mysqlinterface->execute();
				
				$this->mysqlinterface->select(array(
				'articles'=>array(
					'condition'=>array(
						'`name`="Lorem Ipsum #'.$itemname.'"'
					),
					'sort'=>array(
						'desc'=>'`id`'
					),
					'limit'=>1
				)));
				$id = $this->mysqlinterface->execute();

				$status['mnew'] = $id[0]['id'];
				$status['inew'] = $id[0]['id'];
				$status['status'] = 'ok';
				$status['code'] = 200;
			} catch (Exception $e) {
				$status['status'] = 'failed';
				$status['code'] = 417;
			}
		}
		else
		{
			$status['status'] = 'perm: '.$_SESSION['perm'];
			$status['code'] = 403;
		}
		break;
	case 'removeItem::action':
		if(($_SESSION['perm']>=1111)&&($this->logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$this->mysqlinterface->delete(array('menu'=>array(
				'condition'=>array(
					'`id`='.$ajax['item'],
				)
			)));
			$this->mysqlinterface->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
		break;
	case 'forgot':
		$status=$this->logman->forgot();
		if($status['code']===200)
		{
			$this->email->to($_POST['email']);
			$this->email->from('info@'.$_SERVER['SERVER_NAME']);
			$this->email->subject('Code for reset password - '.$_SERVER['SERVER_NAME']);
			$this->email->html();
			$this->emailhead->encode();
			$this->email->body($this->emailhead->save()."<body><p>Hello {$_POST['email']},</p><p>your code for reset password is <b>{$status['confirm-code']}</b>.</p></body></html>");
			$this->email->send();
		}
		break;
	case 'newpass':
		$formatModel='%Y-%m-%d %H:%i:%s';
		$dateTime=date('Y-m-d H:i:s');
		$this->mysqlinterface->select(array(
			'usersPasswordCodes'=>array(
				'condition'=>array(
					"`code`='{$_POST['fgotcode']}'",
					"`expire`>STR_TO_DATE('{$dateTime}', '{$formatModel}')",
					"`param`=0"
				),
				'sort'=>array(
					'desc'=>'`id`'
				)
			)
		));
		$data = $this->mysqlinterface->execute();
		$data = $data[0];
		
		if(($_POST['eregpass']==$_POST['eregrtp'])&&($_POST['eregpass']!=''))
		{
			$filtered_pass = str_ireplace(array(
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
			), $_POST['eregpass']);
			
			if($data['user_id']>0)
			{
				$this->mysqlinterface->update(array(
					'usersPasswordCodes'=>array(
						'condition'=>array(
							'`user_id`='.$data['user_id']
						),
						'set'=>array('param'=>1)
					)));
				$this->mysqlinterface->update(array(
					'users'=>array(
						'condition'=>array(
							'`id`='.$data['user_id']
						),
						'set'=>array(
							'sign'=>'forgot_password',
							'date'=>date('Y-m-d'),
							'time'=>date('H:i:s')
						),
						'set()'=>array(
							'pass'=>"MD5('{$filtered_pass}')",
						)
					)));
				$this->mysqlinterface->execute();
				$this->mysqlinterface->select(array(
					'users'=>array(
						'condition'=>array(
							"`id`='{$data['user_id']}'"
						)
					)
				));
				$data = $this->mysqlinterface->execute();
				$status['code']=200;
			
				$this->email->to($data['email']);
				$this->email->from('info@'.$_SERVER['SERVER_NAME']);
				$this->email->subject('Reset password - '.$_SERVER['SERVER_NAME']);
				$this->email->html();
				$this->emailhead->encode();
				$this->email->body($this->emailhead->save()."<body><p>Hello {$data['fname']},</p><p>your new password is set.</p></body></html>");
				$this->email->send();
			}
			else
			{
				$status['code']=403;
			}
		}
		else
		{
			$status['code']=409.1;
		}
		break;
	case 'removeUser::action':
		if(($_SESSION['perm']>=1111)&&($this->logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$this->mysqlinterface->delete(array('users'=>array(
				'condition'=>array(
					'`id`='.$ajax['user'],
					'`perm`<1111'
				)
			)));
			$this->mysqlinterface->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'removePage::action':
		if(($_SESSION['perm']>=1111)&&($this->logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$this->mysqlinterface->delete(array('articles'=>array(
				'condition'=>array(
					'`id`='.$ajax['page_id'],
					'author_user_id'=>$_SESSION['userid']
				)
			)));
			$this->mysqlinterface->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'removePerm::action':
		if(($_SESSION['perm']>=1111)&&($this->logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$this->mysqlinterface->delete(array('perm_list'=>array(
				'condition'=>array(
					'`id`='.$ajax['perm_id']
				)
			)));
			$this->mysqlinterface->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'killSession::action':
		if(($_SESSION['perm']>=1111)&&($this->logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$this->mysqlinterface->update(array('users'=>array(
				'condition'=>array(
					'`id`='.$ajax['user'],
					'`perm`<1111'
				),
				'set'=>array(
					'sign'=>'kicked'
				)
			)));
			$this->mysqlinterface->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'banUser::action':
		if(($_SESSION['perm']>=1111)&&($this->logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$this->mysqlinterface->update(array('users'=>array(
				'condition'=>array(
					'`id`='.$ajax['user'],
					'`perm`<1111'
				),
				'set'=>array(
					'sign'=>'banned',
					'active'=>-1,
					'perm'=>0,
					
				)
			)));
			$this->mysqlinterface->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'library::changestatus':
		if(($_SESSION['perm']>=1111)&&($this->logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$this->mysqlinterface->update(array('virtual_system_config'=>array(
				'condition'=>array(
					'`id`='.$ajax['lib'],
					'`perm`>=0'
				),
				'set'=>array(
					'switch'=>$ajax['libstatus']
				)
			)));
			$this->mysqlinterface->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'module::changestatus':
		if(($_SESSION['perm']>=1111)&&($this->logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$this->mysqlinterface->update(array('virtual_system_config'=>array(
				'condition'=>array(
					'(`id`='.$ajax['mod'].' OR `function`="cfg:'.$ajax['mod'].'")',
					'`perm`>=0'
				),
				'set'=>array(
					'switch'=>$ajax['modstatus']
				)
			)));
			$this->mysqlinterface->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'gallery::fileupload':
		if(($_SESSION['perm']>=1110)&&($this->logman->checkSession()))
		{
			$fileUpload=new upload(str_ireplace('fw_modules/admin','',dirname( __FILE__ )).'/fw_media/media_imgs/');
			$fileUpload->setMimes(array('image/jpeg','image/png'));
			$fileUpload->maxSize(300000);
			$fileUpload->ajaxSendJson(true);
			$status = $fileUpload->upload();
			$status['status']['filename'] = $fileUpload->name();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'gallery::remove':
		if(($_SESSION['perm']>=1111)&&($this->logman->checkSession()))
		{
			$fileDelete=new fdel('./'.str_ireplace('./','/',$this->url.$ajax['remove']));
			$fileDelete->deleteFile('');
			$status['debug'] = $fileDelete->debug();
			$code = $ajax['code'];
			$status['removecode'] = $code;
			$status['code'] = 200;
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'gdpr::remove_user':
		if(($_SESSION['perm']>=1100)&&($this->logman->checkSession())&&(class_exists('gdpr'))&&($this->logman->getSessionData('userid')==$ajax['code']))
		{
			$gdpr=new gdpr;
			$gdpr->removeAllUserDataFromDB($ajax['remove'],$ajax['code']);
			$status['code'] = 200;
			$status['status'] = 'User #'.$ajax['remove'].' removed.';
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'messages::all_as_read':
		if(($_SESSION['perm']>=1110)&&($this->logman->checkSession())&&(class_exists('statistics')))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$this->mysqlinterface->update(array(
			'waiting_msg'=>array(
				'condition'=>array(
					'`status`=0'
				),
				'set'=>array(
					'status'=>1
				)
			)));
			$this->mysqlinterface->execute();
		}
	break;
}
unset($status['user']);
?>