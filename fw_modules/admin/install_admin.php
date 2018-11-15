<?php
$info='admin';
class module_installer extends construct
{
	/**
	 * Main logic
	 */ 
	final protected function mainLogic()
	{
		$cfg = $this->readVSC('admin');
		$this->config_mod($cfg[0],$cfg[1],$cfg[2]);
		if($this->logman->getSessionData('perm')>=1111)
		{
			$this->automaticInstaller();
		}
	}
	
	/**
	 * Install form
	 */
	//($config_id,$host,$mod,$protocol,$template,$other)
	final private function automaticInstaller()
	{
		$this->mysqlinterface->select(array(
			'virtual_system_config'=>array(
				'condition'=>array(
					'`module`="'.$_POST['mod'].'"'
				)
			)
		));
		$modInstall = $this->mysqlinterface->execute();
		if(($modInstall[0]['module']!=$_POST['mod'])&&($_POST['hidden_cake']=='install_mod'))
		{
			
			$this->mysqlinterface->filter(array(
				'virtual_system_config'=>array(
					'`id`,max(`id`) AS `max_id`'
				)
			));
			$this->mysqlinterface->select(array(
				'virtual_system_config'=>array(
					'condition'=>array(
						'`id`>0'
					)
				)
			));
			$config_id = $this->mysqlinterface->execute();
			$this->install((int)$config_id[0]['max_id']+1,$this->url,$_POST['mod'],str_ireplace('://','',PROTOCOL),$_POST['template_url'],$_POST['other']);
		}
	}
}
?>