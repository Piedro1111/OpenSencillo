<?php
class structgen 
{
	protected $data=array();
	protected $baseCtr;
	protected $subCtr;
	
	public function __construct($name,$mysqlObject)
	{
		$this->data['sql']=$mysqlObject;
		$this->data['structure']=array(
			$name=>array(
				'id'=>array(
					'type'=>'int',
					'primary_key'=>true,
					'auto_increment'=>true
				),
				'base'=>array(
					'type'=>'int'
				),
				'subnav'=>array(
					'type'=>'int'
				),
				'priority'=>array(
					'type'=>'int'
				),
				'perm'=>array(
					'type'=>'int(4)'
				),
				'name'=>array(
					'type'=>'varchar(50)'
				),
				'link'=>array(
					'type'=>'varchar(255)'
				),
			)
		);
		$this->data['name']=$name;
		$this->data['sql']->dbCreateTable($this->data['structure']);
	}
	
	public function add($item,$base=0,$perm=1000,$link=null,$subnav=null,$priority=null)
	{
		$this->data['name']=$name;
	}
	
	public function __destruct()
	{
		$this->data['sql']->execute();
	}
}
?>