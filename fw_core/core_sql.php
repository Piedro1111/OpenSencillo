<?PHP
/*~ core_sql.php
.---------------------------------------------------------------------------.
|  Software: Sencillo Core                                                  |
|   Version: 2014.012                                                       |
|   Contact: ph@mastery.sk                                                  |
| ------------------------------------------------------------------------- |
|    Author: Bc. Peter Horváth (original founder)                           |
| Copyright (c) 2014, Bc. Peter Horváth. All Rights Reserved.               |
| ------------------------------------------------------------------------- |
|   License: Distributed under the General Public License (GPL)             |
|            http://www.gnu.org/copyleft/gpl.html                           |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'---------------------------------------------------------------------------'
~*/
/**
 * Main mysql functions
 * @name Sencillo Core - SQL support
 * @version 2014.012
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

	/**
	 * Create connection
	 * @param string $DBHost
	 * @param string $DBName
	 * @param string $DBUser
	 * @param string $DBPass
	 */
	public function __construct($DBHost,$DBName,$DBUser,$DBPass)
	{
		$this->DBHost = $DBHost;
		$this->DBName = $DBName;
		$this->DBUser = $DBUser;
		$this->DBPass = $DBPass;
		
		if(($this->DBHost!='')&&($this->DBUser!='')&&($this->DBPass!='')&&($this->DBName!=''))
		{
			$this->checksum=md5($this->DBHost.$this->DBUser.$this->DBPass.$this->DBName);
		}
		$this->con = mysql_connect($this->DBHost, $this->DBUser, $this->DBPass);
		if(! $this->con)
		{
			die("<b>core_sql: MySQL connection failed!</b> ".mysql_error());
		}
		mysql_select_db($this->DBName, $this->con);
	}
	
	/**
	 * Add query to database
	 * @param string $sql
	 */
	final public function query($sql)
	{
		mysql_query($sql);
	}
	
	/**
	 * Add query to database
	 * @param string $sql
	 */
	final public function write($sql)
	{
		$this->query($sql);
	}
	
	/**
	 * Close database connection
	 */
	final public function close()
	{
		mysql_close($this->con);
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
				return mysql_error();
			}
			else
			{
				return true;
			}
		}
	}
}

/**
 * Main mysql extend
 * @name Sencillo Core - mysqlEdit
 * @version 2015.002
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
		$this->con=mysql_connect($this->DBHost,$this->DBUser,$this->DBPass);
		mysql_select_db($this->DBName, $this->con);
		$this->result=mysql_query($this->sql);
		$this->column=null;
		while($row=mysql_fetch_array($this->result))
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
			$this->query('DELETE FROM `'.$this->table.'` WHERE `id`>0;');
		}
		else
		{
			$this->query('DELETE FROM `'.$this->table.'` WHERE '.$if.';');
		}
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
		$this->con=mysql_connect($this->DBHost,$this->DBUser,$this->DBPass);
		mysql_select_db($this->DBName, $this->con);
		$this->result=mysql_query($this->sql);
		$this->colout=explode(",",str_replace("`","",substr($this->column, 0, -1)));
		$i=0;
		$j=0;
		$this->out = array('header'=>$this->colout,'line'=>array(array()));
		$this->csum = md5($this->con);
		while($row=mysql_fetch_array($this->result))
		{
			$i=0;
			$j++;
			foreach($this->colout as $val)
			{
				$this->out['line'][$j][$i++]=$row[$val];
			}
		}
		return $this->out;
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
	
	/**
	 *	Create table
	 *	@example SQL array construction:
	 *	array(
	 *		'table'=>array(
	 *			'col1'=>array(
	 *				'type'=>'int',
	 *				'primary_key'=>true,
	 *				'FOREGIN_key'=>'cudzia_tabulka(stlpec)',
	 *				'unique'=>true,
	 *				'auto_increment'=>true,
	 *				'null'=>true
	 *			),
	 *			'col2'=>array(
	 *				'type'=>'int',
	 *				'primary_key'=>true,
	 *				'FOREGIN_key'=>'cudzia_tabulka(stlpec)',
	 *				'unique'=>true,
	 *				'auto_increment'=>true,
	 *				'null'=>true
	 *			),
	 *			'col3'=>array(
	 *				'type'=>'int',
	 *				'primary_key'=>true,
	 *				'FOREGIN_key'=>'cudzia_tabulka(stlpec)',
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
		foreach($array as $key=>$val)
		{
			foreach($val as $key_col=>$val_col)
			{
				$data=null;
				
				foreach($val_col as $key_att=>$val_att)
				{
					switch(strtlower($key_att))
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
						case 'foregin_key':
							$data.=($val_col[$key_att]===false ? '' : ',FOREGIN KEY ('.$key_col.') REFERENCES '.$val_att);
							break;
						case 'unique':
							$data.=($val_col[$key_att]===false ? '' : ',UNIQUE ('.$key_col.')');
							break;
					}
				}
				$this->construct .= ',`'.$key_col.'` '.$data.'';
			}
			$this->save .= 'CREATE TABLE IF NOT EXISTS `'.$key.'` ('.substr($this->construct,1).');';
		}
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
	 *		'ignore_first'=>100,
	 *		'ignore_last'=>200
	 *	)
	 * );
	 * 
	 * @param array $array
	 */
	public function select($array)
	{
		foreach($array as $key=>$val)
		{
			
			foreach($val as $key_col=>$val_col)
			{
				$this->save.='SELECT * FROM '.$key_col.' ';
				$data=null;
		
				foreach($val_col as $key_att=>$val_att)
				{
					switch(strtlower($key_col))
					{
						case 'condition':
							switch(strtlower($key_att))
							{
								case 0:
									$data_condition='WHERE '.$val_att;
									break;
								default:
									$data_condition.=' AND '.$val_att;
									break;
								case 'or':
									$data_condition.=' OR '.$val_att;
									break;
							}
						break;
						case 'sort':
							switch(strtlower($key_att))
							{
								case 'asc':
									$data_sort=' ORDER BY '.$val_att.' ASC';
									break;
								case 'desc':
									$data_sort=' ORDER BY '.$val_att.' DESC';
									break;
							}
						break;
						case 'start':
							$data_limit_start=$val_att.',';
						break;
						case 'limit':
							$data_limit_max=' '.$val_att;
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
				$this->save.=$data_condition.$data_sort.(isset($data_limit_max)? ' LIMIT '.$data_limit_start.$data_limit_max : '').';';
			}
		}
		/**
		 * @TODO out - addcode
		 */
		
		return $select;
	}
	
	/**
	 * Create database protected configuration arrray
	 */
	public function config()
	{
		$this->mysqli=array(
			'dbhost'=>$this->$DBHost,
			'dbname'=>$this->$DBName,
			'dbuser'=>$this->$DBUser,
			'dbpass'=>$this->$DBPass
		);
	}
	
	/**
	 * Create database connection
	 */
	public function connect()
	{
		$this->connect = new mysqli($this->mysqli['dbhost'], $this->mysqli['dbuser'], $this->mysqli['dbpass'], $this->mysqli['dbname']);
		if($this->connect->connect_errno)
		{
			$this->mysqli['dberror'] = "Failed to connect to MySQL: (" . $this->connect->connect_errno . ") " . $this->connect->connect_error;
		}
	}
	
	/**
	 * Execute database multiline query
	 */
	public function execute()
	{
		if(!$this->connect->multi_query($this->save))
		{
			$this->mysqli['dberror'] = "Multi query failed: (" . $this->connect->errno . ") " . $this->connect->error;
		}
		
		do 
		{
			if ($res = $this->connect->store_result())
			{
				$res->fetch_all(MYSQLI_ASSOC);
				$res->free();
			}
		}
		while ($this->connect->more_results() && $this->connect->next_result());
	}
}
$mysql = new mysqlEdit($DBHost,$DBName,$DBUser,$DBPass);
?>