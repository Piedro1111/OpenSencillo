<?php
/*~ mod_identificator.php
.---------------------------------------------------------------------------.
|  Software: Sencillo Libraries Identificator                               |
|   Version: 2014.011                                                       |
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
 * Create connection to library
 * @todo Test needed
 * @name modules
 * @version 2014.011
 * @category libraries
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class library
{
	protected $all_data_sencillo;
	public $lib;
	protected $readsql;
	protected $files;
	
	/**
	 * Create complet data structure
	 */
	private function createStructure()
	{
	    $this->lib=array("id"=>array(),
	                     "function"=>array(),
	                     "boxid"=>array(),
	                     "status"=>array());
	    $this->all_data_sencillo=array("left"=>array(),
	                                   "center"=>array(),
	                                   "right"=>array(),
	                                   "foot"=>array(),
	                                   "admin"=>array());
	    $this->readsql="SELECT * FROM cms_boxid";
	    $this->files = scandir('./fw_libraries/');
	}
	
	/**
	 * Open modules
	 */
	private function openFiles()
	{
	    foreach($this->files as $value)
	    {
			$test=(($value!='.')&&($value!='..')&&($value!='lib_identificator.php')&&($value!='examples')?true:false);
	    	
	        if(($value!='.')&&($value!='..')&&($value!='lib_identificator.php')&&($value!='examples'))
	        {
	            $this->lib['id'][]=$value;
	        }
	    }

		foreach($this->lib['id'] as $value)
		{
		    try
		    {
		    	//require("./fw_libraries/".$value);
		    	$NAME=explode(".",$value);
		    	$MOD_DESC=$NAME[0].','.$NAME[1];
		    	$this->lib['name'][]=$NAME[2];
		    	$this->lib['function'][]=$MOD_DESC;
		    	$this->lib['version'][]=$VERSION;
		    	$this->lib['status'][]='OK:'.$value;
		    	$this->lib['path'][]="./fw_libraries/".$value;
		    }
		    catch(Exception $e)
		    {
		        $this->lib['status']=array('ERROR:'.$value.':'.$e);
		    }
		}
	}
	
	/**
	 * Start main mod_id proces
	 */
	public function start()
	{
	    $this->createStructure();
	    $this->openFiles();
	}
	
	/**
	 * Export data obtained after main proces
	 * @return mixed [(left/right/center/foot/admin)] || [(numeric value)]
	 */
	public function export()
	{
	    return $this->all_data_sencillo;
	}
	
	/**
	 * Export informations about libraries
	 * @return array
	 */
	public function status()
	{
		return $this->lib;
	}
}
?>
