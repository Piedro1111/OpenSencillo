<?php
/**
 * Create connection to library
 * @name modules and library loader
 * @version 2015.109
 * @category Sencillo Library
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/licenses/gpl-3.0.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class library
{
	protected $all_data_sencillo;
	protected $readsql;
	protected $files;
	protected $modules;
	
	public $lib;
	
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
		$this->files = scandir(__DIR__ . '/fw_libraries/');
		if(file_exists(__DIR__ . "/fw_modules/"))
		{
			$this->modules = scandir(__DIR__ . '/fw_modules/');
		}
	}
	
	/**
	 * Open libraries
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
				$this->lib['path'][]=__DIR__ . "/fw_libraries/".$value;
				$this->lib['install'][]="../fw_libraries/".$value;
			}
			catch(Exception $e)
			{
				$this->lib['status']=array('ERROR:'.$value.':'.$e);
			}
		}
	}
	
	/**
	 * Open modules
	 */
	private function openModules()
	{
		foreach($this->modules as $value)
		{
			$test=((file_exists(__DIR__ . "/fw_modules/".$value."/"))&&($value!='.')&&($value!='..')&&($value!='lib_identificator.php')&&($value!='examples')?true:false);
	
			if((file_exists(__DIR__ . "/fw_modules/".$value."/"))&&($value!='.')&&($value!='..')&&($value!='lib_identificator.php')&&($value!='examples'))
			{
				$this->lib['id'][]=$value;
			}
		}
	
		foreach($this->lib['id'] as $value)
		{
			try
			{
				$this->lib['name'][]=$value;
				$this->lib['function'][]='custom_module';
				$this->lib['status'][]='OK:'.$value;
				$this->lib['path'][]=__DIR__ . "/fw_modules/".$value."/info_".$value.".php";//information about module
				$this->lib['path'][]=__DIR__ . "/fw_modules/".$value."/update_".$value.".php";//update database for module
				$this->lib['path'][]=__DIR__ . "/fw_modules/".$value."/install_".$value.".php";//installer
				$this->lib['path'][]=__DIR__ . "/fw_modules/".$value."/main_".$value.".php";//main module
				$this->lib['path'][]=__DIR__ . "/fw_modules/".$value."/".$value.".php";//basic module
			}
			catch(Exception $e)
			{
				$this->lib['status'][]='ERROR:'.$value.':'.$e;
			}
		}
	}
	
	/**
	 * Install main mod_id. Safe start only.
	 * @param array $ignored
	 */
	public function install($ignored)
	{
		$this->createStructure();
		$this->files = array_diff(scandir('../fw_libraries/'),$ignored);
		$this->openFiles();
	}
	
	/**
	 * Start main mod_id proces
	 */
	public function start()
	{
		$this->createStructure();
		$this->openFiles();
		$this->openModules();
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
	
	/**
	 * Return array with unique path for class loader
	 * @return arrray
	 */
	public function exportPath()
	{
		return array_unique($this->lib['path']);
	}
	
	/**
	 * Return array with unique installer path for installer class loader
	 * @return arrray
	 */
	public function installerPath()
	{
		return array_unique($this->lib['install']);
	}
}
?>
