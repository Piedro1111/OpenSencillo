<?PHP
/**
 * Main mysql functions
 * @name Sencillo Core - SQL support
 * @version 2017.104
 * @category core
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class mysql
{
	public  $DBHost;
	public  $DBName;
	public  $DBUser;
	public  $DBPass;
	private $checksum;
	public  $con;
	public  $debug;

	/**
	 * Create connection
	 * @param string $DBHost
	 * @param string $DBName
	 * @param string $DBUser
	 * @param string $DBPass
	 */
	public function __construct($DBHost=null,$DBName=null,$DBUser=null,$DBPass=null)
	{
		if(!empty($DBHost))
		{
			$this->DBHost = $DBHost;
			$this->DBName = $DBName;
			$this->DBUser = $DBUser;
			$this->DBPass = $DBPass;
			
			if(($this->DBHost!='')&&($this->DBUser!='')&&($this->DBPass!='')&&($this->DBName!=''))
			{
				$this->checksum=md5($this->DBHost.$this->DBUser.$this->DBPass.$this->DBName);
			}
			$this->con = mysqli_connect($this->DBHost, $this->DBUser, $this->DBPass);
			if(! $this->con)
			{
				die("<b>core_sql: MySQL connection failed!</b> ".mysqli_error());
			}
			mysqli_select_db($this->DBName, $this->con);
		}
	}
	
	/**
	 * Add query to database
	 * @param string $sql
	 * @return mixed resources
	 */
	final public function query($sql)
	{
		return mysqli_query($this->con,$sql);
	}
	
	/**
	 * Add query to database
	 * @param string $sql
	 * @return mixed resources
	 */
	final public function write($sql)
	{
		return $this->query($sql);
	}
	
	/**
	 * Close database connection
	 * @return mixed
	 */
	final public function close()
	{
		return mysqli_close($this->con);
	}
	
	/**
	 * Test connection
	 * @return mixed
	 */
	final public function test()
	{
		if($this->checksum==md5($this->DBHost.$this->DBUser.$this->DBPass.$this->DBName))
		{
			if(! $this->con)
			{
				return mysqli_error();
			}
			else
			{
				return true;
			}
		}
	}
	
	/**
	 * Integrity check
	 * @param string database type
	 */
	final public function integrity($type)
	{
		$handle = fopen("firststart.json", "r");
		$contents = fread($handle, filesize($filename));
		fclose($handle);
		$contents = json_decode($contents,false);
		if(md5($_SERVER['SERVER_NAME'].$_SERVER['SERVER_ADDR'].$this->DBHost.$this->DBUser.$type)!=$contents->hash)
		{
			die('Integrity_Error: Illegal system operation!');
		}
		return true;
	}
}

/**
 * Main mysql extend
 * @name Sencillo Core - mysqlEdit
 * @version 2017.104
 * @category core
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class mysqlEdit extends mysql
{
	private $construct;
	private $key;
	private $table;
	private $sql;
	private $result;
	private $column;
	private $setupdate;
	private $colout;
	private $out;
	private $metaout;
	private $csum;
	private $sizeout;

	/**
	 * Create new column with type
	 * @param string $name column
	 * @param string $type column
	 */
	public function newColumn($name,$type="INT")
	{
		$this->construct .= ' , `'.$name.'` '.strtoupper($type).'';
	}
	
	/**
	 * Light alternative to openTable
	 * @param string $name
	 */
	public function prepareTable($name)
	{
		$this->table = $name;
	}
	
	/**
	 * Create unique key. Use after prepareTable.
	 * @param string $keyName
	 */
	public function uniqueKey($keyName)
	{
		$this->key .= ' , UNIQUE KEY `'.$this->table.'` (`'.$keyName.'`)';
	}
	
	/**
	 * Create table (use after newColumn function)
	 * @name string $name
	 */
	public function createTable($name)
	{
		$this->table = $name;
		$this->query('CREATE TABLE IF NOT EXISTS `'.$name.'` ( `id` INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(`id`)'.$this->construct.');');
		$this->construct = null;
	}
	
	/**
	 * Open table and read all column names
	 * @param string $name
	 */
	public function openTable($name)
	{
		$this->table = $name;
		$this->sql="SHOW COLUMNS FROM ".$this->DBName.".".$this->table;
		$this->con=mysqli_connect($this->DBHost,$this->DBUser,$this->DBPass);
		mysqli_select_db($this->DBName, $this->con);
		$this->result=mysqli_query($this->sql);
		$this->column=null;
		while($row=mysqli_fetch_array($this->result))
		{
			$this->column.='`'.$row['Field'].'`,';
		}
	}
	
	/**
	 * Insert data to created/opened table
	 * @param string $values
	 * @example $this->insert("'test_string',null,NOW(),123")
	 */
	public function insert($values)
	{
		$this->query('INSERT INTO '.$this->table.' ('.substr($this->column, 0, -1).') VALUES (null,'.$values.');');
	}
	
	/**
	 * Use befor update - edit value in the column
	 * @param string $column
	 * @param string $value
	 */
	public function set($column,$value)
	{
		if(is_numeric($value))
		{
			$this->setupdate.='`'.$column.'`='.$value.',';
		}
		else
		{
			$this->setupdate.='`'.$column.'`="'.$value.'",';
		}
	}
	
	/**
	 * Update values in your table
	 * @param string $if
	 * @param string $sets
	 * @example $this->update("id=1","`data`='your_data'")
	 */
	public function update($if,$sets=null)
	{
		$this->query('UPDATE '.$this->table.' SET '.substr($this->setupdate, 0, -1).$sets.' WHERE '.$if.';');
	}
	
	/**
	 * Delete specific or all data
	 * @param string $if
	 * @example $this->delete("all")
	 * @example $this->delete("`id`=1")
	 */
	public function delete($if)
	{
		if($if=="all")
		{
			$var = 'DELETE FROM `'.$this->table.'` WHERE `id`>0;';
		}
		else
		{
			$var = 'DELETE FROM `'.$this->table.'` WHERE '.$if.';';
		}
		$this->query($var);
	}
	
	/**
	 * Output after open table
	 * @param string $if
	 * @param string $order
	 * @param integer $limit
	 * @example $this->output("`id`<4000","`id` DESC",2000)
	 */
	public function output($if="`id`>0",$order="`id` ASC",$limit=1000)
	{
		$this->sql="SELECT * FROM `".$this->table."` WHERE ".$if." ORDER BY ".$order." LIMIT ".$limit.";";
		$this->con=mysqli_connect($this->DBHost,$this->DBUser,$this->DBPass);
		mysqli_select_db($this->con,$this->DBName);
		
		$this->result=mysqli_query($this->con,$this->sql);
		
		/*$this->colout=explode(",",str_replace("`","",substr($this->column, 0, -1)));
		$i=0;
		$j=0;
		$this->out = array('header'=>$this->colout,'line'=>array(array()));
		$this->csum = md5($this->con);*/
		$i=1;
		while($row=mysqli_fetch_array($this->result))
		{
			$this->out['line'][$i++]=$row;
		}
		return $this->out;
	}
	
	public function testout()
	{
		$sql="SELECT * FROM `users`;";
		$con=mysqli_connect($this->DBHost,$this->DBUser,$this->DBPass);
		mysqli_select_db($con,$this->DBName);
		
		$result=mysqli_query($con,$sql);
		$i=0;
		while($row=mysqli_fetch_array($result))
		{
			$out['line'][$i++]=$row;
		}
		return $out;
	}
}

/**
 * @TODO test need
 */
class mysqlInterface extends mysqlEdit
{
	protected $save;
	protected $mysqli;
	protected $connect;
	private $default;
	
	/**
	 *	Create table
	 *	@example SQL array construction:
	 *	array(
	 *		'table'=>array(
	 *			'col1'=>array(
	 *				'type'=>'int',
	 *				'primary_key'=>true,
	 *				'foreign_key'=>'foreign_table(foreign_col)',
	 *				'foreign_key'=>array('foreign table'=>'foreign col'),
	 *				'unique'=>true,
	 *				'auto_increment'=>true,
	 *				'null'=>true
	 *			),
	 *			'col2'=>array(
	 *				'type'=>'int',
	 *				'primary_key'=>true,
	 *				'foreign_key'=>'foreign_table(foreign_col)',
	 *				'foreign_key'=>array('foreign table'=>'foreign col'),
	 *				'unique'=>true,
	 *				'auto_increment'=>true,
	 *				'null'=>true
	 *			),
	 *			'col3'=>array(
	 *				'type'=>'int',
	 *				'primary_key'=>true,
	 *				'foreign_key'=>'foreign_table(foreign_col)',
	 *				'foreign_key'=>array('foreign table'=>'foreign col'),
	 *				'unique'=>true,
	 *				'auto_increment'=>true,
	 *				'null'=>true
	 *			)
	 *		)
	 *	)
	 *	@example $this->dbCreateTable(array([...]))
	 *	@param array $array
	 */
	public function dbCreateTable($array)
	{
		$foreignGenerator='';
		foreach($array as $key=>$val)
		{
			foreach($val as $key_col=>$val_col)
			{
				$data=null;
				
				foreach($val_col as $key_att=>$val_att)
				{
					switch(strtolower($key_att))
					{
						case 'type':
							$data.=strtoupper($val_col[$key_att]);
							break;
						case 'null':
							$data.=($val_col[$key_att]===false ? ' NOT NULL' : '');
							break;
						case 'auto_increment':
							$data.=($val_col[$key_att]===false ? '' : ' AUTO_INCREMENT');
							break;
						case 'primary_key':
							$data.=($val_col[$key_att]===false ? '' : ',PRIMARY KEY ('.$key_col.')');
							break;
						case 'foreign_key':
							if(is_string($val_att))
							{
								$this->query('SET foreign_key_checks = 1');
								$data.=($val_col[$key_att]===false ? '' : ',FOREIGN KEY ('.$key_col.') REFERENCES '.$val_att);
							}
							else
							{
								foreach($val_att as $key_val_att=>$sub_val_att)
								{
									$foreignGenerator = $this->addForeignKey($key, $key_val_att, $key_col, $sub_val_att);
								}
							}
							break;
						case 'unique_key':
						case 'unique':
							$data.=($val_col[$key_att]===false ? '' : ',UNIQUE KEY ('.$key_col.')');
							break;
					}
				}
				$this->construct .= ',`'.$key_col.'` '.$data.'';
			}
			$this->save .= 'CREATE TABLE IF NOT EXISTS `'.$key.'` ('.substr($this->construct,1).');'.$foreignGenerator;
		}
	}
	
	/**
	 * Foreign key
	 * @param string master table
	 * @param string reference table
	 * @param string master
	 * @param string reference
	 */
	private function addForeignKey($masterTable, $referenceTable, $master, $reference)
	{
		$construct = "SET foreign_key_checks=1;";
		$construct .= "ALTER TABLE $masterTable ADD FOREIGN KEY ($master) REFERENCES $referenceTable ($reference);";
		return $construct;
	}
	
	/**
	 * Insert data
	 * @param array
	 * @example array(
	 * 	'table'=>array(
	 *   'col'=>'data',
	 *   'col'=>'data',
	 *  )
	 * )
	 */
	public function insert($array,$stringRewrite=true)
	{
		foreach($array as $key=>$val)
		{
			$this->save.='INSERT INTO `'.$key.'`';
			$col=' (';
			$values=' VALUES (';
			foreach($val as $sub_key=>$sub_val)
			{
				$col.='`'.$sub_key.'`,';
				if((is_string($sub_val))&&($stringRewrite))
				{
					$values.="'".$sub_val."',";
				}
				else
				{
					$values.=$sub_val.',';
				}
			}
			$col=substr($col, 0, -1);
			$values=substr($values, 0, -1);
			$col=$col.')';
			$values=$values.')';
			$this->save.=$col.$values.';';
		}
	}
	
	/**
	 * Prepare join before using select
	 * @param array
	 * @example array input:
	 * array(
	 * 	'table'=>array(
	 * 	 col1,col2,col3
	 * 	)
	 * )
	 */
	public function filter($def)
	{
		foreach($def as $key=>$val)
		{
			foreach($val as $sub_key=>$sub_val)
			{
				$this->default.=$val.'.'.$sub_val.',';
			}
		}
		$this->default=substr($this->default,0,-1);
	}
	
	/**
	 * Select by array structure
	 *
	 * @example array structure:
	 * array(
	 * 	'table'=>array(
	 *		'where'=>array(
	 *			'`id`<4000',
	 *			'`data`=1',
	 * 			'or'=>'`data2`=2'
	 *		),
	 *		'set'=>array(
	 *			'col1'=>'data',
	 *			'col2'=>'data'
	 *		)
	 *	)
	 * );
	 *
	 * @param array $array
	 */
	public function update($array)
	{
		$this->select($array,true);
	}
	
	/**
	 * Select by array structure
	 * 
	 * @example array structure:
	 * array(
	 * 	'table'=>array(
	 *		'condition'=>array(
	 *			'`id`<4000',
	 *			'`data`=1',
	 * 			'or'=>'`data2`=2'
	 *		),
	 *		'sort'=>array(
	 *			'asc'=>'`id`',
	 *			'desc'=>'`id`'
	 *		),
	 *		'start'=>2000,
	 *		'limit'=>1000,
	 *		'join'=>array(
	 *			'table'=>array('colA','colB')
	 *		),
	 *		'ignore_first'=>100,
	 *		'ignore_last'=>200
	 *	)
	 * );
	 * 
	 * @param array $array
	 * @param bool
	 */
	public function select($array,$update=false)
	{
		if(empty($this->default))
		{
			$this->default='*';
		}
		foreach($array as $key=>$val)
		{
			if($update)
			{
				$this->save.='UPDATE `'.$key.'` ';
			}
			else
			{
				$this->save.='SELECT '.$this->default.' FROM `'.$key.'` ';
			}
			
			foreach($val as $key_col=>$val_col)
			{
				$data=null;
		
				switch(strtolower($key_col))
				{
					case 'if':
					case 'where':
					case 'condition':
						$data_condition.=' WHERE ';
						foreach($val_col as $key_att=>$val_att)
						{
							switch(strtolower($key_att))
							{
								case '0':
									$data_condition.=$val_att;
									break;
								case 'or':
									$data_condition.=' OR '.$val_att;
									break;
								default:
									$data_condition.=' AND '.$val_att;
									break;
							}
						}
					break;
					case 'between':
						$data_condition.=' BETWEEN '.$key_att.' AND '.$val_att;
					break;
					case 'set':
						foreach($val_col as $key_att=>$val_att)
						{
							$data_set.="`{$key_att}`='{$val_att}',";
						}
					break;
					case 'set()':
						foreach($val_col as $key_att=>$val_att)
						{
							$data_set.="`{$key_att}`={$val_att},";
						}
					break;
					case 'sort':
						$data_sort=' ORDER BY ';
						$data_sort_arr=array();
						foreach($val_col as $key_att=>$val_att)
						{
							switch(strtolower($key_att))
							{
								case 'asc':
									$data_sort_arr[]=$val_att.' ASC';
									break;
								case 'desc':
									$data_sort_arr[]=$val_att.' DESC';
									break;
							}
						}
						$data_sort.=implode(',',$data_sort_arr);
					break;
					case 'like':
						$data_like=' LIKE '.$val_col;
					break;
					case 'start':
						$data_limit_start=$val_col.',';
					break;
					case 'limit':
						$data_limit_max=' '.$val_col;
					break;
					case 'fulljoin':
					case 'fjoin':
					case 'full':
						$data_join.=' FULL OUTER JOIN '.$key_att.' ON '.$key.'.'.$val_att[0].'='.$key_att.'.'.$val_att[1];
					break;
					case 'innerjoin':
					case 'ijoin':
					case 'join':
					case 'inner':
						$data_join.=' INNER JOIN '.$key_att.' ON '.$key.'.'.$val_att[0].'='.$key_att.'.'.$val_att[1];
					break;
					case 'leftjoin':
					case 'ljoin':
					case 'left':
						$data_join.=' INNER JOIN '.$key_att.' ON '.$key.'.'.$val_att[0].'='.$key_att.'.'.$val_att[1];
					break;
					case 'rightjoin':
					case 'rjoin':
					case 'right':
						$data_join.=' INNER JOIN '.$key_att.' ON '.$key.'.'.$val_att[0].'='.$key_att.'.'.$val_att[1];
					break;
					case 'ignore_first':
						/**
						 * @TODO ignore first N items
						 */
					break;
					case 'ignore_last':
						/**
						 * @TODO ignore last N items
						 */
					break;
				}
			}
			$this->save.=(isset($data_set)?' SET '.substr($data_set,0,-1):'').$data_join.$data_condition.$data_like.$data_sort.(isset($data_limit_max)? ' LIMIT '.$data_limit_start.$data_limit_max : '').';';
		}
		/**
		 * @TODO out - addcode
		 */
		
		return $this->save;
	}
	
	/**
	 * Delete specific or all data
	 * @param string $if
	 * @example $this->delete("all")
	 * @example $this->delete(array)
	 * @example array structure:
	 * array(
	 * 	'table'=>array(
	 *		'condition'=>array(
	 *			'`id`<4000',
	 *			'`data`=1',
	 * 			'or'=>'`data2`=2'
	 *		)
	 *	);
	 */
	public function delete($if)
	{
		$var = null;
		foreach($if as $key=>$val)
		{
			foreach($val as $key_col=>$val_col)
			{
				switch(strtolower($key_col))
				{
					case 'if':
					case 'where':
					case 'condition':
						$data_condition.=' WHERE ';
						foreach($val_col as $key_att=>$val_att)
						{
							switch(strtolower($key_att))
							{
								case '0':
									$data_condition.=$val_att;
									break;
								case 'or':
									$data_condition.=' OR '.$val_att;
									break;
								default:
									$data_condition.=' AND '.$val_att;
									break;
							}
						}
					break;
				}
			}
			$var.= 'DELETE FROM `'.$key.'`'.$data_condition.';';
		}
		$this->save .= $var;
	}
	
	/**
	 * Add SQL query
	 * @param string $sql
	 */
	public function addQuery($sql)
	{
		$this->save.=$sql;
	}
	
	/**
	 * Create database protected configuration arrray
	 */
	public function config()
	{
		if(empty($this->DBHost))
		{
			$this->mysqli=array(
				'dbhost'=>database::host,
				'dbname'=>database::name,
				'dbuser'=>database::user,
				'dbpass'=>database::pass
			);
		}
		else
		{
			$this->mysqli=array(
				'dbhost'=>$this->DBHost,
				'dbname'=>$this->DBName,
				'dbuser'=>$this->DBUser,
				'dbpass'=>$this->DBPass
			);
		}
	}
	
	/**
	 * Create database connection
	 */
	public function connect()
	{
		$this->config();
		$this->connect = new mysqli($this->mysqli['dbhost'], $this->mysqli['dbuser'], $this->mysqli['dbpass'], $this->mysqli['dbname']);
		$this->connect->set_charset("utf8");
		if($this->connect->connect_errno)
		{
			$this->mysqli['dberror']['message'] = "Failed to connect to MySQL: (" . $this->connect->connect_errno . ") " . $this->connect->connect_error;
			$this->mysqli['dberror']['code']	= 'mysqlInterface:001';
			try 
			{
				log::vd($this->mysqli);
			} 
			catch(Exception $e) 
			{
				var_dump($this->mysqli);
			}
			
			die();
		}
	}
	
	/**
	 * Check SQL code for error
	 * @return bool
	 */
	public function validator()
	{
		if(!$this->connect->multi_query($this->save))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	/**
	 * Write query error log
	 * @see validator()
	 * @return string
	 */
	public function debug()
	{
		return ($this->validator()? 'QUERY:'.$this->save.' ERR:'.$this->connect->errno : 'Query is OK');
	}
	
	/**
	 * Execute database multiline query and return result
	 * @return array[group_id][line_id][row_name]
	 */
	public function execute()
	{
		if($this->save)
		{
			if(!$this->connect->multi_query($this->save))
			{
				$this->mysqli['dberror']['query']	= $this->save;
				$this->mysqli['dberror']['message']	= "Multi query failed: (" . $this->connect->errno . ") " . $this->connect->error;
				$this->mysqli['dberror']['code']	= 'mysqlInterface:002';
				try 
				{
					log::vd($this->mysqli);
				} 
				catch(Exception $e)
				{
					var_dump($this->mysqli);
				}
			}
			else 
			{
				$this->save = null;
			}
			
			$result=array();
			do 
			{
				if($res = $this->connect->store_result())
				{
					while ($row = $res->fetch_array())
					{
						$result[] = $row;
					}
					$res->free();
				}
			}
			while($this->connect->more_results() && $this->connect->next_result());
		}
		else
		{
			die('Unknown SQL');
		}
		
		return $result;
	}
}

if(empty($hash['hash']))
{
	$mysql = new mysqlEdit($DBHost,$DBName,$DBUser,$DBPass);
}
else
{
	$mysql = new mysqlInterface();
	$mysql->config();
}
?>
