<?php
class gdpr extends construct
{
	private $cfg;
	private $privatedata;
	private $filegdpr;
	
	final protected function mainLogic()
	{
		$this->cfg = $this->readVSC('gdpr');
		$this->config_mod($this->cfg[0],$this->cfg[1],$this->cfg[2]);
	}
	final public function getAllUserDataFromDB($login,$id)
	{
		$this->mysqlinterface->select(array(
			'users'=>array(
				'condition'=>array(
					'`id`="'.$this->logman->getSessionData('userid').'"',
					'`login`="'.$login.'"'
				)
			)
		));
		$data = $this->mysqlinterface->execute();
		if($data[0]['id']==$id)
		{
			
		
			$this->mysqlinterface->showtables();
			$data['all_tables'] = $this->mysqlinterface->execute();
			
			foreach($data['all_tables'] as $key=>$val)
			{
				$condition=false;
				switch($val[0])
				{
					case 'articles':
						$condition='((`article` LIKE "%'.$login.'%") OR (`author_user_id`="'.$id.'"))';
					break;
					case 'console':
						$condition='(`data` LIKE "%'.$login.'%")';
					break;
					case 'gdpr_approvals':
						$condition='(`userid`="'.$id.'")';
					break;
					case 'login':
						$condition='(`userid`="'.$id.'")';
					break;
					case 'newsletter_recipients':
						$condition='(`email`="'.$login.'")';
					break;
					case 'posted_form_data':
						$condition='(`val`="'.$login.'")';
					break;
					case 'statistics':
						$condition='(`user_id`="'.$id.'")';
					break;
					case 'users':
						$condition='((`id`="'.$id.'") OR (`login`="'.$login.'") OR (`email`="'.$login.'"))';
					break;
					case 'userPasswordCodes':
						$condition='(`user_id`="'.$id.'")';
					break;
				}
				if($condition)
				{
					$this->mysqlinterface->select(array(
						$val[0]=>array(
							'condition'=>array(
								$condition
							)
						)
					));
					$data[$val[0]] = $this->mysqlinterface->execute();
				}
			}
			unset($data['all_tables']);
			$this->privatedata=$data;
			return $data;
		}
	}
	final public function removeAllUserDataFromDB($login,$id)
	{
		$this->mysqlinterface->select(array(
			'users'=>array(
				'condition'=>array(
					'`id`="'.$this->logman->getSessionData('userid').'"',
					'`login`="'.$login.'"'
				)
			)
		));
		$data = $this->mysqlinterface->execute();
		if($data[0]['id']==$id)
		{
			
		
			$this->mysqlinterface->showtables();
			$data['all_tables'] = $this->mysqlinterface->execute();
			
			foreach($data['all_tables'] as $key=>$val)
			{
				$condition=false;
				switch($val[0])
				{
					/*case 'articles':
						$condition='((`article` LIKE "%'.$login.'%") OR (`author_user_id`="'.$id.'"))';
					break;*/
					case 'console':
						$condition='(`data` LIKE "%'.$login.'%")';
					break;
					case 'gdpr_approvals':
						$condition='(`userid`="'.$id.'")';
					break;
					case 'login':
						$condition='(`userid`="'.$id.'")';
					break;
					case 'newsletter_recipients':
						$condition='(`email`="'.$login.'")';
					break;
					case 'posted_form_data':
						$condition='(`val`="'.$login.'")';
					break;
					case 'statistics':
						$condition='(`user_id`="'.$id.'")';
					break;
					case 'users':
						$condition='((`id`="'.$id.'") OR (`login`="'.$login.'") OR (`email`="'.$login.'"))';
					break;
					case 'userPasswordCodes':
						$condition='(`user_id`="'.$id.'")';
					break;
				}
				if($condition)
				{
					$this->mysqlinterface->delete(array(
						$val[0]=>array(
							'condition'=>array(
								$condition
							)
						)
					));
				}
			}
			$this->mysqlinterface->execute();
		}
	}
	final public function gdprtable()
	{
		foreach($this->privatedata as $key=>$val)
		{
			if(is_string($key))
			{
				$t[]='<tr>';
				$t[]='<td>';
				$t[]=str_ireplace('_',' ',ucfirst($key));
				$t[]='</td>';
				$t[]='<td>';
				$t[]='Ready to export';
				$t[]='</td>';
				$t[]='<td>';
				$t[]=date('d.m.Y H:i:s');
				$t[]='</td>';
				$t[]='</tr>'.PHP_EOL;
			}
		}
		$this->filegdpr=$this->logman->getSessionData('userid').'_'.date('YmdHis').'_privacy.json';
		$filesystem=new fileSystem('./fw_media/gdpr/'.$this->filegdpr);
		$filesystem->write(json_encode($this->privatedata,JSON_PRETTY_PRINT));
		return implode('',$t);
	}
	final public function gdprfilename()
	{
		return $this->filegdpr;
	}
}
?>
