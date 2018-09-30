<?php
$logman=new logMan;
$email=new mailGen;
$emailhead=new headerSeo;

$mysql = new mysqlInterface;
$mysql->config();
$mysql->connect();

$log=$logman->getSignedUser();
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
		$status = $logman->login($ajax);
		//var_dump($logman->testout());
		break;
	case 'ereg':
		$logman->openTable('users');
		if(filter_var($_POST[$ajax['atype'].'email'], FILTER_VALIDATE_EMAIL))
		{
			if($_POST[$ajax['atype'].'pass']===$_POST[$ajax['atype'].'rtp'])
			{
				$status['user']=$logman->output("`login`='".strtolower($ajax[$ajax['atype'].'email'])."'","`id` ASC",1);
				if(empty($status['user']['line'][1][0]))
				{
					try {
						$name = explode(" ",$_POST[$ajax['atype'].'fullname']);
						$logman->insert("'first_use',0,'" . strtolower($_POST[$ajax['atype'].'email']) . "',MD5('" . $_POST[$ajax['atype'].'pass'] . "'),'" . strtolower($_POST[$ajax['atype'].'email']) . "','" . $logman->clean(ucwords(strtolower($name[0]))) . "','" . $logman->clean(ucwords(strtolower($name[1]))) . "',1000,'" . $log['external_ip'] . ":" . $log['port'] . "','" . $log['agent'] . "',DATE(NOW()),TIME(NOW())");
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
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			$email = date('yzHis')."@".$_SERVER['SERVER_NAME'];
			try {
				$name = explode(" ",'User User');
				
				$mysql->insert(array(
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
				$mysql->execute();
				
				$mysql->select(array(
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
				$id = $mysql->execute();

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
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			try {
				$itemname = substr(md5(date('Ymdhis')),0,4);
				$mysql->insert(array(
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
				$mysql->execute();
				
				$mysql->select(array(
				'menu'=>array(
					'condition'=>array(
						'`item`="new #'.$itemname.'"'
					),
					'sort'=>array(
						'desc'=>'`id`'
					),
					'limit'=>1
				)));
				$id = $mysql->execute();

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
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			try {
				$itemname = substr(md5(date('Ymdhis')),0,4);
				$mysql->insert(array(
				'perm_list'=>array(
					'id'=>'',
					'perm'=>0,
					'usertype'=>'#'.$itemname
				)));
				$mysql->execute();
				
				$mysql->select(array(
				'perm_list'=>array(
					'condition'=>array(
						'`usertype`="#'.$itemname.'"'
					),
					'sort'=>array(
						'desc'=>'`id`'
					),
					'limit'=>1
				)));
				$id = $mysql->execute();

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
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			try {
				$itemname = substr(md5(date('Ymdhis')),0,4);
				$mysql->insert(array(
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
				$mysql->execute();
				
				$mysql->select(array(
				'articles'=>array(
					'condition'=>array(
						'`name`="Lorem Ipsum #'.$itemname.'"'
					),
					'sort'=>array(
						'desc'=>'`id`'
					),
					'limit'=>1
				)));
				$id = $mysql->execute();

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
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$mysql->delete(array('menu'=>array(
				'condition'=>array(
					'`id`='.$ajax['item'],
				)
			)));
			$mysql->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
		break;
	case 'forgot':
		$status=$logman->forgot();
		if($status['code']===200)
		{
			$email->to($_POST['email']);
			$email->from('info@'.$_SERVER['SERVER_NAME']);
			$email->subject('Code for reset password - '.$_SERVER['SERVER_NAME']);
			$email->html();
			$emailhead->encode();
			$email->body($emailhead->save()."<body><p>Hello {$_POST['email']},</p><p>your code for reset password is <b>{$status['confirm-code']}</b>.</p></body></html>");
			$email->send();
		}
		break;
	case 'newpass':
		$formatModel='%Y-%m-%d %H:%i:%s';
		$dateTime=date('Y-m-d H:i:s');
		$mysql->select(array(
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
		$data = $mysql->execute();
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
				$mysql->update(array(
					'usersPasswordCodes'=>array(
						'condition'=>array(
							'`user_id`='.$data['user_id']
						),
						'set'=>array('param'=>1)
					)));
				$mysql->update(array(
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
				$mysql->execute();
				$mysql->select(array(
					'users'=>array(
						'condition'=>array(
							"`id`='{$data['user_id']}'"
						)
					)
				));
				$data = $mysql->execute();
				$status['code']=200;
			
				$email->to($data['email']);
				$email->from('info@'.$_SERVER['SERVER_NAME']);
				$email->subject('Reset password - '.$_SERVER['SERVER_NAME']);
				$email->html();
				$emailhead->encode();
				$email->body($emailhead->save()."<body><p>Hello {$data['fname']},</p><p>your new password is set.</p></body></html>");
				$email->send();
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
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$mysql->delete(array('users'=>array(
				'condition'=>array(
					'`id`='.$ajax['user'],
					'`perm`<1111'
				)
			)));
			$mysql->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'removePage::action':
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$mysql->delete(array('articles'=>array(
				'condition'=>array(
					'`id`='.$ajax['page_id'],
					'author_user_id'=>$_SESSION['userid']
				)
			)));
			$mysql->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'removePerm::action':
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$mysql->delete(array('perm_list'=>array(
				'condition'=>array(
					'`id`='.$ajax['perm_id']
				)
			)));
			$mysql->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'killSession::action':
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$mysql->update(array('users'=>array(
				'condition'=>array(
					'`id`='.$ajax['user'],
					'`perm`<1111'
				),
				'set'=>array(
					'sign'=>'kicked'
				)
			)));
			$mysql->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'banUser::action':
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$mysql->update(array('users'=>array(
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
			$mysql->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'library::changestatus':
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$mysql->update(array('virtual_system_config'=>array(
				'condition'=>array(
					'`id`='.$ajax['lib'],
					'`perm`>=0'
				),
				'set'=>array(
					'switch'=>$ajax['libstatus']
				)
			)));
			$mysql->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
	case 'module::changestatus':
		if(($_SESSION['perm']>=1111)&&($logman->checkSession()))
		{
			$status['code'] = 200;
			$status['status'] = 'ok';
			$mysql->update(array('virtual_system_config'=>array(
				'condition'=>array(
					'`id`='.$ajax['mod'],
					'`perm`>=0'
				),
				'set'=>array(
					'switch'=>$ajax['modstatus']
				)
			)));
			$mysql->execute();
		}
		else
		{
			$status['code'] = 403;
			$status['status'] = 'denied';
		}
	break;
}
unset($status['user']);
?>