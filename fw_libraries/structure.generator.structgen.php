<?php
/**
 * Menu or structure generator
 * @name Sencillo Structgen
 * @version 2015.005
 * @category libraries
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class structgen 
{
	protected $data=array();
	protected $sql;
	protected $baseCtr;
	protected $subCtr;
	protected $name;
	
	public function __construct($name,$mysqlObject)
	{
		$this->sql=$mysqlObject;
	}
	
	/**
	 * Create structure
	 * @param $name string
	 */
	public function createStructure($name='structgen')
	{
		$this->data['structure'][]=array(
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
		$this->name=$name;
	}
	
	/**
	 * Create unistore list structure
	 * @param $name string
	 */
	public function uniStore($name='uniStructgen')
	{
		$this->data['structure'][]=array(
			$name=>array(
				'id'=>array(
					'type'=>'int',
					'primary_key'=>true,
					'auto_increment'=>true
				),
				'mainBase'=>array(
					'type'=>'int'
				),
				'subBase'=>array(
					'type'=>'int'
				),
				'priority'=>array(
					'type'=>'int'
				),
				'perm'=>array(
					'type'=>'int(4)'
				),
				'dataName'=>array(
					'type'=>'varchar(50)'
				),
				'vchVal'=>array(
					'type'=>'varchar(255)'
				),
				'floatVal'=>array(
					'type'=>'varchar(255)'
				),
				'intVal'=>array(
					'type'=>'varchar(255)'
				),
				'datetime'=>array(
					'type'=>'datetime'
				),
			)
		);
		$this->name=$name;
	}
	
	/**
	 * Switch to other structure by name
	 * @param $name string
	 */
	public function switchStructure($name)
	{
		$this->name=$name;
	}
	
	/**
	 * Add item / record to structure
	 * @param $item string name of item 
	 * @param $base int id of parent (root base is 0)
	 * @param $subnav int id of new item
	 * @param $perm int permission for view
	 * @param $link string full URL
	 * @param $priority int priority level
	 */
	public function add($item,$base=0,$subnav=null,$perm=1000,$link=null,$priority=null)
	{
		$this->data['add'][]=array(
			$this->name=>array(
				'id'=>"''",
				'base'=>$base,
				'subnav'=>$subnav,
				'priority'=>$priority,
				'perm'=>$perm,
				'name'=>$item,
				'link'=>$link
			)
		);
	}
	
	/**
	 * Create database queries for construct full structure and execute queries
	 */
	private function createQueries()
	{
		foreach($this->data['structure'] as $val)
		{
			$this->sql->dbCreateTable($val);
		}
		foreach($this->data['add'] as $val)
		{
			$this->sql->insert($val);
		}
		$this->sql->execute();
	}
	
	/**
	 * List structure
	 * @param $rootbase int
	 * @param $maxbase int
	 */
	public function listStructure()
	{
		$structure = $this->worker();
		$arr=array();
		$arrstrc=array();
		foreach($structure as $key=>$val)
		{
			foreach($structure as $keyB=>$valB)
			{
				if($valB['subnav']==$val['base'])
				{
					$arrstrc[]=$valB;
					unset($structure[$keyB]);
				}
			}
			$arr[$val['base']]=$arrstrc;
		}
	}
	
	/**
	 * List all structure information for one base (for one level)
	 * @param $base int
	 * @return array
	 */
	public function listBase($base)
	{
		$structure = $this->worker();
		foreach($structure as $key=>$val)
		{
			if($base!=$val['base'])
			{
				unset($structure[$key]);
			}
		}
		return $structure;
	}
	/**
	 * Find all content for list
	 * @return array
	 */
	private function worker()
	{
		$allBase=array(
			$this->name=array(
				'condition'=>array(
					'`id`>=0',
				),
				'sort'=>array(
					'asc'=>'`id`'
				)
			)
		);
		$this->sql->select($allBase);
		$arr = $this->sql->execute();
		$sizearr = sizeof($arr);
		return array('sqlreturn'=>$arr,'recordsctr'=>$sizearr);
	}
	
	public function __destruct(){}
}

class defaultStructures extends structgen
{
	
}
?>