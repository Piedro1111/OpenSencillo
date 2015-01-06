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
$mysql = new mysqlEdit($DBHost,$DBName,$DBUser,$DBPass);
?>