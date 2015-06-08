<?php
/**
 * Menu generator
 * @name menuGen
 * @version 2015.005
 * @category Sencillo Library
 * @see http://www.opensencillo.com
 * @author Radoslav Ambrózy, Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/licenses/gpl-3.0.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
 
class menuGen
{
	protected $mysqlObject;
	
	protected $categories_maxlevel;
	
	protected $levels = array();
	public $maxLevel= array();
	
	protected $lvls = array();
	protected $sorted = array();
	
	public function __construct($mysqlObject)
	{
		$this->mysqlObject = $mysqlObject;
		
		$this->categories_maxlevel = $this->mysqlObject->query("SELECT MAX(`level_id`) AS maxlevel FROM `categories` LIMIT 1");
		
		while($data = mysql_fetch_assoc($this->categories_maxlevel))
		{
			$this->maxLevel[] = $data;
		}
		
		for($i = 0; $i < $this->maxLevel[0]['maxlevel']; $i++)
		{
			$this->levels[$i] = $this->mysqlObject->query("SELECT * FROM `categories` WHERE `level_id`=".($i+1)." ORDER BY `category_href` ASC");
		}	
	}
	
	
	/**
	 * Sort 2D array by key
	 * @examlpe $sorted = keySort($array, 'id');
	 * @param 2D array
	 * @param string
	 * 
	 * @return sorted array
	 */
	private function keySort($array, $key)
	{
		//get vlaues of key
		foreach($array as $k=>$v)
		{
			$b[] = strtolower($v[$key]);
		}
		
		//sort by key
		asort($b);
		
		foreach($b as $k=>$v)
		{
			$c[] = $array[$k];
		}
		
		return $c;
	}
	
	
	/**
	 * Generates menu level
	 * 
	 * @param int
	 * 
	 * @return string
	 */
	public function generateLevel($level)
	{
		$menu_level = $this->sorted[$level];

		if(sizeof($menu_level) > 0)
		{
			foreach($menu_level as $lvl)
			{
				$arr[] = "<li class='menu-level-".$level."'><a href='http://".$_SERVER['SERVER_NAME']."/produkty/".$lvl['category_href']."'>".$lvl['category_name']."</a></li>";
			}
			
		}
		else
		{
			$arr[] = "<p>ERROR - Menu level doesnt exist!</p>";
		}
		
		return implode("\n", $arr);
	}
	
	
	/**
	 * Generates menu from DB
	 * @todo render
	 * @param array
	 * @param string
	 * 
	 * @return sorted array
	 */
	public function generateMenu()
	{
		for($i = 0; $i < $this->maxLevel[0]['maxlevel']; $i++)
		{
			if($this->levels[$i]){
				while($data = mysql_fetch_assoc($this->levels[$i])){
					$this->lvls[$i][] = $data;
				}
				
				$this->sorted[$i] = $this->keySort($this->lvls[$i], 'sort');
			}
		}
		
		//unitTest::vd($this->lvls);
		//unitTest::vd($this->sorted);
		
		//*todo************************************************************************************************************************************************************************
		$arr[] = "<ul class='menu'>";
		
			foreach($this->sorted[0] as $lvl_1){
				$arr[] = "<li class='menu-level-1'><a href='http://".$_SERVER['SERVER_NAME']."/produkty/".$lvl_1['category_href']."'>".$lvl_1['category_name']."</a>";
				
				foreach($this->sorted[1] as $lvl_2){
					if($lvl_2['subcategory_id'] == $lvl_1['category_id']){
						$arr[] = "<li class='menu-level-2'><a href='http://".$_SERVER['SERVER_NAME']."/produkty/".$lvl_2['category_href']."'>".$lvl_2['category_name']."</a>";
						
						foreach($this->sorted[2] as $lvl_3){
							if($lvl_3['subcategory_id'] == $lvl_2['category_id']){
								$arr[] = "<li class='menu-level-3'><a href='http://".$_SERVER['SERVER_NAME']."/produkty/".$lvl_3['category_href']."'>".$lvl_3['category_name']."</a>";
							}
						}
						$arr[] = "</li>";
					}
				}
				$arr[] = "</li>";
			}
		$arr[] = "</ul>";
		//*todo************************************************************************************************************************************************************************
		
		return implode("\n", $arr);
	}	
}
?>