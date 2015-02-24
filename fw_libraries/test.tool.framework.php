<?php 
/**
 * Error Log class
 * @name log
 * @version 2015.003
 * @category Sencillo Library
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/licenses/gpl-3.0.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class log
{
    protected $name;
    protected $file;
    protected $path;
    protected $current;
    protected $store=array();
    protected $maxLine=0;
    protected $useFile;
   
	/**
	 * @param string $path
	 * @param string $file
	 * @param number $maxLine
	 * @param bool	 $useFile
	 */
	final public function __construct($path='',$file='log.txt',$maxLine=100,$useFile=true,$mysql=null)
	{
	    $this->file = $path.$file;
	    $this->path = $path;
	    $this->name = $file;
	    $this->maxLine = $maxLine;
	    $this->useFile = $useFile;
	    $this->store['mysql'] = $mysql;
	}

	/**
	 * Save to log
	 * @param string $data
	 */
	final public function saveToLog($data)
	{
		if($this->useFile)
		{
		    $this->getContent();
		    $this->current = fopen($this->file, "w");
		    fprintf($this->current, "%s\t%s\t%s\t%s\t%s\t%s\n",date('Y-m-d'),date('H:i:s'),$data[0],$data[1],$data[2],$data[3]);
		    foreach ($this->store as $val)
		    {
		        fprintf($this->current, "%s\t%s\t%s\t%s\t%s\t%s\n",$val[0],$val[1],$val[2],$val[3],$val[4],$val[5]);
		    }
		    
		    fclose($this->current);
		    
		    if($this->maxLine<sizeof($this->store))
		    {
		    	rename($_SERVER['DOCUMENT_ROOT'] . $this->file,$_SERVER['DOCUMENT_ROOT'] . $this->path ."old_" . $this->name);
		    }
		}
		else
		{
		    $name=explode('.',$this->name);
		    $this->store['mysql']->newColumn('date','VARCHAR(10)');
		    $this->store['mysql']->newColumn('time','VARCHAR(8)');
		    $this->store['mysql']->newColumn('data0','TEXT');
		    $this->store['mysql']->newColumn('data1','TEXT');
		    $this->store['mysql']->newColumn('data2','TEXT');
		    $this->store['mysql']->newColumn('data3','TEXT');
		    $this->store['mysql']->createTable($name[0].'_log');
		    $this->store['mysql']->openTable($name[0].'_log');
		    $this->store['mysql']->insert('\''.date('Y-m-d').'\',\''.date('H:i:s').'\',\''.$data[0].'\',\''.$data[1].'\',\''.$data[2].'\',\''.$data[3].'\'');
		}
	}
	
	/**
	 * Read log file
	 */
	private function getContent()
	{
		if($this->useFile)
		{
			$this->current = fopen($this->file, "r");
		    $i=0;
		    while(($buffer = fgets($this->current, 4096)) !== false)
		    {
			    $this->store[$i++] = sscanf($buffer, "%s\t%s\t%s\t%s\t%s\t%s\n");
		    }
			fclose($this->current);
		}
		else
		{
			return $this->output("`id`>0","`id` DESC",$this->maxLine);
		}
	}
	
	/**
	 * Load from log
	 * @return array
	 */
	public function getLog()
	{
		$this->getContent();
		return $this->store;
	}
	
	/**
	 * Get formated var_dump to string
	 */
	public function vd($var)
	{
		ob_start();
	    var_dump($var);
	    $result = ob_get_clean();
	    $result = highlight_string($result,true);
	    $result = str_replace('=&gt;<br />&nbsp;&nbsp;&nbsp;','<b>=&gt;</b>',$result);
	    $result = str_replace(')&nbsp;"',')&nbsp;"<font color="blue">',$result);
	    $result = str_replace('"<br />','</font>"<br />',$result);
	    echo $result;
	    return $result;
	}
}

/**
 * Testing tool - unit testing extension
 * @name unitTest
 * @version 2015.003
 * @category Sencillo Library
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class unitTest extends log
{
	protected $env='public';
	
	/**
	 * Setting for switch environment to public or developers
	 * 
	 * @param bool|string
	 * 
	 * @return mixed
	 */
	public function setEnvironment($public=true)
	{
		$this->env = ($public===true?'public':'developer');
		if($public===false)
		{
			print "<script type='text/javascript'>console.log('<<< Sencillo Testing Tool >>>');</script>";
			error_reporting(E_ALL);
			print "<script type='text/javascript'>console.log('<<< DEVEL MODE ON >>>');</script>";
		}
		return $public;
	}
	
	/**
	 * Simple version of testing command
	 * 
	 * @example $this->print_test([testing data],array([data type]=>null[,[operator]=>[required data],...]))
	 * 
	 * @param mixed
	 * @param array
	 * @param bool
	 */
	public function print_test($function=null,$validator=null,$die=false)
	{
		return $this->print_ut($this->init($function,$validator),$die);
	}
	
	/**
	 * Initialize testing tool
	 * 
	 * @example $this->init(myFunction(),array('string'=>null,'=='=>'text'));
	 * @example $this->init(myFunction(),array('int'=>null,'>'=>6));
	 * @example $this->init(6,array('int'=>null));
	 * 
	 * @example main commands: string=>null,
	 * 							   int=>null,
	 * 							 float=>null,
	 * 							  bool=>null,
	 * 							 email=>null,
	 * 							    ip=>null,
	 * 							   url=>null,
	 * 							regexp=>null,
	 * 							 array=>null,
	 * 							object=>null,
	 * 							   == =>value,
	 * 							   != =>value,
	 * 							    < =>value,
	 * 							    > =>value,
	 * 							   <= =>value,
	 * 							   >= =>value
	 * 
	 * @param mixed
	 * @param mixed
	 * 
	 * @return mixed
	 */
	public function init($function=null,$validator=null)
	{
		if((is_array($validator))&&($this->env==='developer'))
		{
			foreach($validator as $key=>$val)
			{
				switch($key)
				{
					case '%s':
					case 'string':
						if(!(is_string($function)))
						{
							return array("status"=>"no$key",
										 "value"=>var_export($function,true)
							);
						}
						break;
					case '%d':
					case 'int':
					case 'integer':
						if(!(is_integer($function)))
						{
							return array("status"=>"no$key",
										 "value"=>var_export($function,true)
							);
						}
						break;
					case '%f':
					case 'float':
						if(!(is_float($function)))
						{
							return array("status"=>"no$key",
										 "value"=>var_export($function,true)
							);
						}
						break;
					case '%b':
					case 'bool':
					case 'boolean':
						if(!(is_bool($function)))
						{
							return array("status"=>"no$key",
										 "value"=>var_export($function,true)
							);
						}
						break;
					case 'email':
						if(!(filter_var($function, FILTER_VALIDATE_EMAIL)))
						{
							return array("status"=>"no$key",
										 "value"=>var_export($function,true)
							);
						}
						break;
					case 'ip':
						if(!(filter_var($function, FILTER_VALIDATE_IP)))
						{
							return array("status"=>"no$key",
										 "value"=>var_export($function,true)
							);
						}
						break;
					case 'url':
						if(!(filter_var($function, FILTER_VALIDATE_URL)))
						{
							return array("status"=>"no$key",
										 "value"=>var_export($function,true)
							);
						}
						break;
					case 'regexp':
						if(!(filter_var($function, FILTER_VALIDATE_REGEXP)))
						{
							return array("status"=>"no$key",
										 "value"=>var_export($function,true)
							);
						}
						break;
					case 'arr':
					case 'array':
						if(!(is_array($function)))
						{
							return array("status"=>"no$key",
										 "value"=>var_export($function,true)
							);
						}
						break;
					case 'obj':
					case 'object':
						if(!(is_object($function)))
						{
							return array("status"=>"no$key",
										 "value"=>var_export($function,true)
							);
						}
						break;
					case '=':
					case '==':
						if($val!=$function)
						{
							return array("status"=>"no$key",
										 "value"=>var_export($function,true)
							);
						}
						break;
					case '<>':
					case '!=':
						if(!($val!=$function))
						{
							return array("status"=>"no$key",
										 "value"=>var_export($function,true)
							);
						}
						break;
					case '<':
						if(!($val>$function))
						{
							return array("status"=>"no$key",
									 	 "value"=>var_export($function,true)
							);
						}
						break;
					case '>':
						if(!($val<$function))
						{
							return array("status"=>"no$key",
										 "value"=>var_export($function,true)
							);
						}
						break;
					case '<=':
						if(!($val>=$function))
						{
							return array("status"=>"no$key",
										 "value"=>var_export($function,true)
							);
						}
						break;
					case '>=':
						if(!($val<=$function))
						{
							return array("status"=>"no$key",
										 "value"=>var_export($function,true)
							);
						}
						break;
				}
			}
			return array("status"=>"ok",
					"output"=>$function,
					"value"=>var_export($function,true)
			);
		}
		return $function;
	}
	
	/**
	 * Create var_dump
	 * @param mixed
	 */
	public function print_ut($export,$die=true)
	{
		if($this->env==='developer')
		{
			if($export['status']=="ok")
			{
				$console='info';
			}
			else 
			{
				$console='error';
			}
			print "<script type='text/javascript'>console.$console('status: ".$export['status']."');</script>";
			print "<script type='text/javascript'>console.$console('value: ".$export['value']."');</script>";
			if($die){die('STOP:500:by Unit Test');}
		}
		return $export;
	}
}
?>