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
	protected $href;
	protected $sitemap;
	protected $name;
	
	public function __construct($mysqlObject){
		
		$this->mysqlObject = $mysqlObject;
	}
	
	/**
	 * Add item to menu
	 * @param int base
	 * @param int subbase
	 * @param int priority level
	 * @param string name
	 * @param string href relative path as my/own/url
	 * @param string title text
	 * @param string image full path
	 * @param string image text alternative
	 */
	public function addItemToMenu($cBase,$cSubBase,$priority,$perm,$cName,$cHref,$cTitle,$cImage,$cImageAlt)
	{
		$name = $this->name;
		$this->mysqlObject->dbCreateTable(array(
			$name=>array(
				'id'=>"''",
				'category_id'=>$cBase,
				'subcategory_id'=>$cSubBase,
				'sort'=>$priority,
				'perm'=>$perm,
				'category_name'=>"'$cName'",
				'category_href'=>"'$cHref'",
				'category_title'=>"'$cTitle'",
				'category_img'=>"'$cImage'",
				'category_img_alt'=>"'$cImageAlt'"
			)
		));
		$this->mysqlObject->execute();
	}
	
	/**
	 * Create menu structure and add new table to database
	 * @param string table name
	 */
	public function createMenu($name)
	{
		$this->name = $name;
		$this->mysqlObject->dbCreateTable(array(
			$name=>array(
				'id'=>array('type'=>'int','auto_increment'=>true,'primary_key'=>true),
				'category_id'=>array('type'=>'int'),
				'subcategory_id'=>array('type'=>'int'),
				'sort'=>array('type'=>'int'),
				'perm'=>array('type'=>'int(4)'),
				'category_name'=>array('type'=>'varchar(250)'),
				'category_href'=>array('type'=>'varchar(250)'),
				'category_title'=>array('type'=>'varchar(250)'),
				'category_img'=>array('type'=>'varchar(250)'),
				'category_img_alt'=>array('type'=>'varchar(250)')
			)
		));
		$this->mysqlObject->execute();
	}
	
	/**
	 * Switch menu by table name
	 * @param string table name
	 */
	public function switchMenu($name)
	{
		$this->name = $name;
	}
	
	/**
	 * Generates an unlimited structured menu from DB
	 * @example echo createMenu(0, 0, "0-10");
	 *
	 * @param int example 0
	 * @param int example 0
	 * 
	 * @return string
	 */
	public function generateMenu($subcategory, $level)
	{
		$db = $this->name;
		$query = $this->mysqlObject->query("SELECT menu.category_id, menu.category_name, menu.category_href, menu.sort, Deriv1.count FROM " . "`" .$db. "`" . " menu  LEFT OUTER JOIN (SELECT subcategory_id, COUNT(*) AS count FROM ". "`" .$db. "`". " GROUP BY subcategory_id) Deriv1 ON menu.category_id = Deriv1.subcategory_id WHERE menu.subcategory_id=".$subcategory.' ORDER BY menu.sort ASC');
		$arr[] = "<ul class='menu-ul-level-".$level."'>";
		while ($data = mysql_fetch_assoc($query))
		{
			$this->href = 'http://'.$_SERVER['SERVER_NAME']."/".$data['category_href'];
			$this->sitemap[] = array('priority'=>$level,'href'=>$this->href);
			//if there are subcategories
			if ($data['count'] > 0)
			{
				$arr[] = "<li class='menu-li-level-".$level."'><a class='menu-a-level-".$level."' href='".$this->href."'>".$data['category_name']."</a>";
				$arr[] = $this->generateMenu($data['category_id'], $level + 1);
				$arr[] = "</li>";
				//no subcategories
			} else if ($data['count'] == 0)
			{
				$arr[] = "<li class='menu-li-level-".$level."'><a class='menu-a-level-".$level."' href='".$this->href."'>".$data['category_name']."</a></li>";
			}
		}
		$arr[] = "</ul>";
		return implode("\n", $arr);
	}
}
?>