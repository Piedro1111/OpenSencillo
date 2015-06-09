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
	
	public function __construct($mysqlObject){
		
		$this->mysqlObject = $mysqlObject;
	}
	
	
	/**
	 * Generates an unlimited structured menu from DB
	 * @examlpe echo createMenu("categories", 0, 0, "0-10");
	 *
	 * @param string table name
	 * @param int example 0
	 * @param int example 0
	 * @param string example 0-10
	 * 
	 * @return string
	 */
	public function generateMenu($db, $subcategory, $level, $limit)
	{
		$query = $this->mysqlObject->query("SELECT menu.category_id, menu.category_name, menu.category_href, menu.sort, Deriv1.count FROM " . "`" .$db. "`" . " menu  LEFT OUTER JOIN (SELECT subcategory_id, COUNT(*) AS count FROM ". "`" .$db. "`". " GROUP BY subcategory_id) Deriv1 ON menu.category_id = Deriv1.subcategory_id WHERE menu.subcategory_id=".$subcategory.' ORDER BY menu.sort ASC');
		$arr[] = "<ul class='menu_ul_level_".$level."'>";
		while ($data = mysql_fetch_assoc($query))
		{
			$href = 'http://'.$_SERVER['SERVER_NAME']."/produkty/".$data['category_href']."/".$limit;
			//if there are subcategories
			if ($data['count'] > 0)
			{
				$arr[] = "<li class='menu_li_level_".$level."'><a class='menu_a_level_".$level."' href='".$href."'>".$data['category_name']."</a>";

				$arr[] = $this->generateMenu($db, $data['category_id'], $level + 1, $limit);
				$arr[] = "</li>";
				//no subcategories
			} else if ($data['count'] == 0){				
				$arr[] = "<li class='menu_li_level_".$level."'><a class='menu_a_level_".$level."' href='".$href."'>".$data['category_name']."</a></li>";
			}
		}
		$arr[] = "</ul>";
		return implode("\n", $arr);
	}
	
}
?>