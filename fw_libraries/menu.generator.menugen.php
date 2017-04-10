<?php
/**
 * Menu generator
 * @name menuGen
 * @version 2017.104
 * @category Sencillo Library
 * @see http://www.opensencillo.com
 * @author Radoslav Ambrózy, Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/licenses/gpl-3.0.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
 
class menuGen
{
	protected $mysqlObject;
	protected $href;
	protected $name;
	protected $protocol;
	protected $page;
	protected $language;
	protected $perm;
	protected $maxLevelQuery;
	
	public $maxLevel = array();
	
	public function __construct($mysqlObject,$protocol,$page,$language,$perm)
	{
		$this->mysqlObject	= $mysqlObject;
		$this->protocol		= $protocol;
		$this->page			= $page;
		$this->language		= $language;
		$this->perm			= $perm;
		
		$this->maxLevelQuery = $this->mysqlObject->query("SELECT MAX(`level_id`) AS maxlevel FROM `categories` LIMIT 1");
		
		while($data = mysqli_fetch_assoc($this->maxLevelQuery)){
			$this->maxLevel[] = $data;
		}
	}
	
	/**
	 * Add item to menu
	 * @param int base
	 * @param int subbase
	 * @param int priority level
	 * @param int perm level
	 * @param int language id
	 * @param string name
	 * @param string href relative path as my/own/url
	 * @param string title text
	 * @param string image full path
	 * @param string image text alternative
	 */
	public function addItemToMenu($cBase,$cSubBase,$priority,$perm,$lang,$cName,$cHref,$cTitle,$cImage,$cImageAlt)
	{
		$name = $this->name;
		$this->mysqlObject->dbCreateTable(array(
			$name=>array(
				'id'=>"''",
				'category_id'=>$cBase,
				'subcategory_id'=>$cSubBase,
				'sort'=>$priority,
				'perm'=>$perm,
				'lang'=>$lang,
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
				'lang'=>array('type'=>'int(4)'),
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
		$query = $this->mysqlObject->query("SELECT menu.category_id, menu.category_name, menu.category_href, menu.sort, menu.lang, menu.perm, Deriv1.count FROM " . "`" .$db. "`" . " menu  LEFT OUTER JOIN (SELECT subcategory_id, COUNT(*) AS count FROM ". "`" .$db. "`". " GROUP BY subcategory_id) Deriv1 ON menu.category_id = Deriv1.subcategory_id WHERE menu.subcategory_id=".$subcategory." AND menu.lang=".$this->language." AND menu.perm=".$this->perm." ORDER BY menu.sort ASC");
		$arr[] = "<ul class='menu-ul-level-".$level."'>";
		while($data = mysqli_fetch_assoc($query))
		{
			$this->href = $this->protocol.'://'.$_SERVER['SERVER_NAME']."/".$data['category_href'];
			//if there are subcategories
			if($data['count'] > 0)
			{
				$arr[] = "<li class='menu-li-level-".$level."'><a class='menu-a-level-".$level."' href='".$this->href."'>".$data['category_name']."</a>";
				$arr[] = $this->generateMenu($data['category_id'], $level + 1);
				$arr[] = "</li>";
				//no subcategories
			}
			elseif($data['count'] == 0)
			{
				$arr[] = "<li class='menu-li-level-".$level."'><a class='menu-a-level-".$level."' href='".$this->href."'>".$data['category_name']."</a></li>";
			}
		}
		$arr[] = "</ul>";
		return implode("\n", $arr);
	}	
	
	/**
	 * Generates show/hide menu by level
	 * @todo Must by changed to recursive because maximum level is 1-7
	 * @example echo $menuGen->generateMenuLevel("categories", 1, "0-10", PAGE);
	 *
	 * @param int
	 * @param string
	 * @param string
	 *
	 * @return string
	 */ 
	public function generateMenuLevel($level, $limit, $page){
		$db = $this->name;

		$page = explode('/', $page);
		
		//level 1 menu
		if($level == 1){
			$level_1_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `level_id` = 1 ORDER BY sort");
			
			$arr[] = "<ul>";
				while ($data = mysqli_fetch_assoc($level_1_query)){
					$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$data['category_href']."/".$limit.">".$data['category_name']."</a></li>";
				}
			$arr[] = "</ul>";
			
			return implode("\n", $arr);
		}

		//level 1-2 menu
		if($level == 2){
			$level_1_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `level_id` = 1 ORDER BY sort");
			
			$arr[] = "<ul>";
				while ($level_1 = mysqli_fetch_assoc($level_1_query)){
					$level_2_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_1['category_id']." ORDER BY sort");

					//level 1 page selected
					if($level_1['category_href'] == $page[1]){
						$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_1['category_href']."/".$limit."><b>".$level_1['category_name']."</b></a>";
						
						while ($level_2 = mysqli_fetch_assoc($level_2_query)){
							$arr[] = "<ul>";
								//level 1 page selected has subcategories
								if($level_2['category_href'].substr($page[1])){
									
									$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_2['category_href']."/".$limit.">".$level_2['category_name']."</a></li>";	
								}
							$arr[] = "</ul>";
						}
					} else {
						//level 1 page not selected
						$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_1['category_href']."/".$limit.">".$level_1['category_name']."</a></li>";		
					}
				}
			$arr[] = "</ul>";
			
			return implode("\n", $arr);
		}
		
		//level 1-3 menu
		if($level == 3){
			$level_1_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `level_id` = 1 ORDER BY sort");
			
			$arr[] = "<ul>";
				while ($level_1 = mysqli_fetch_assoc($level_1_query)){
					$level_2_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_1['category_id']." ORDER BY sort");

					//level 1 page selected
					if($level_1['category_href'] == $page[1]){
						$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_1['category_href']."/".$limit."><b>".$level_1['category_name']."</b></a>";
						
						while ($level_2 = mysqli_fetch_assoc($level_2_query)){
							$arr[] = "<ul>";
								//level 1 page selected has subcategories
								if($level_2['category_href'].substr($page[1])){
									$level_3_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_2['category_id']." ORDER BY sort");

									//level 2 page selected
									if($level_2['category_href'] == $page[1]."/".$page[2]){		
										$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_2['category_href']."/".$limit."><b>".$level_2['category_name']."</b></a>";
										
										while ($level_3 = mysqli_fetch_assoc($level_3_query)){
											
											$arr[] = "<ul>";
												//level 2 page selected has subcategories
												if($level_3['category_href'].substr($page[1]."/".$page[2])){
													$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_3['category_href']."/".$limit.">".$level_3['category_name']."</a></li>";	
												}
											$arr[] = "</ul>";
										}
									} else {
										//level 2 page not selected
										$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_2['category_href']."/".$limit.">".$level_2['category_name']."</a></li>";	
									}
								}
							$arr[] = "</ul>";
						}
					} else {
						//level 1 page not selected
						$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_1['category_href']."/".$limit.">".$level_1['category_name']."</a></li>";		
					}
				}
			$arr[] = "</ul>";			

			return implode("\n", $arr);
		}
		
		//level 1-4 menu
		if($level == 4){
			$level_1_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `level_id` = 1 ORDER BY sort");
			
			$arr[] = "<ul>";
				while ($level_1 = mysqli_fetch_assoc($level_1_query)){
					$level_2_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_1['category_id']." ORDER BY sort");

					//level 1 page selected
					if($level_1['category_href'] == $page[1]){
						$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_1['category_href']."/".$limit."><b>".$level_1['category_name']."</b></a>";
						
						while ($level_2 = mysqli_fetch_assoc($level_2_query)){
							$arr[] = "<ul>";
								//level 1 page selected has subcategories
								if($level_2['category_href'].substr($page[1])){
									$level_3_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_2['category_id']." ORDER BY sort");

									//level 2 page selected
									if($level_2['category_href'] == $page[1]."/".$page[2]){		
										$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_2['category_href']."/".$limit."><b>".$level_2['category_name']."</b></a>";
										
										while ($level_3 = mysqli_fetch_assoc($level_3_query)){
											
											$arr[] = "<ul>";
												//level 2 page selected has subcategories
												if($level_3['category_href'].substr($page[1]."/".$page[2])){
													$level_4_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_3['category_id']." ORDER BY sort");

													//level 3 page selected
													if($level_3['category_href'] == $page[1]."/".$page[2]."/".$page[3]){		
														$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_3['category_href']."/".$limit."><b>".$level_3['category_name']."</b></a>";
														
														while ($level_4 = mysqli_fetch_assoc($level_4_query)){
															
															$arr[] = "<ul>";
																//level 3 page selected has subcategories
																if($level_4['category_href'].substr($page[1]."/".$page[2]."/".$page[3])){
																	$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_4['category_href']."/".$limit.">".$level_4['category_name']."</a></li>";	
																}
															$arr[] = "</ul>";
														}
													} else {
														//level 3 page not selected
														$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_3['category_href']."/".$limit.">".$level_3['category_name']."</a></li>";	
													}	
												}
											$arr[] = "</ul>";
										}
									} else {
										//level 2 page not selected
										$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_2['category_href']."/".$limit.">".$level_2['category_name']."</a></li>";	
									}
								}
							$arr[] = "</ul>";
						}
					} else {
						//level 1 page not selected
						$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_1['category_href']."/".$limit.">".$level_1['category_name']."</a></li>";		
					}
				}
			$arr[] = "</ul>";			

			return implode("\n", $arr);
		}
		
		//level 1-5 menu
		if($level == 5){
			$level_1_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `level_id` = 1 ORDER BY sort");
			
			$arr[] = "<ul>";
				while ($level_1 = mysqli_fetch_assoc($level_1_query)){
					$level_2_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_1['category_id']." ORDER BY sort");

					//level 1 page selected
					if($level_1['category_href'] == $page[1]){
						$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_1['category_href']."/".$limit."><b>".$level_1['category_name']."</b></a>";
						
						while ($level_2 = mysqli_fetch_assoc($level_2_query)){
							$arr[] = "<ul>";
								//level 1 page selected has subcategories
								if($level_2['category_href'].substr($page[1])){
									$level_3_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_2['category_id']." ORDER BY sort");

									//level 2 page selected
									if($level_2['category_href'] == $page[1]."/".$page[2]){		
										$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_2['category_href']."/".$limit."><b>".$level_2['category_name']."</b></a>";
										
										while ($level_3 = mysqli_fetch_assoc($level_3_query)){
											
											$arr[] = "<ul>";
												//level 2 page selected has subcategories
												if($level_3['category_href'].substr($page[1]."/".$page[2])){
													$level_4_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_3['category_id']." ORDER BY sort");

													//level 3 page selected
													if($level_3['category_href'] == $page[1]."/".$page[2]."/".$page[3]){		
														$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_3['category_href']."/".$limit."><b>".$level_3['category_name']."</b></a>";
														
														while ($level_4 = mysqli_fetch_assoc($level_4_query)){
															
															$arr[] = "<ul>";
																//level 3 page selected has subcategories
																if($level_4['category_href'].substr($page[1]."/".$page[2]."/".$page[3])){
																	$level_5_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_4['category_id']." ORDER BY sort");

																	//level 4 page selected
																	if($level_4['category_href'] == $page[1]."/".$page[2]."/".$page[3]."/".$page[4]){		
																		$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_4['category_href']."/".$limit."><b>".$level_4['category_name']."</b></a>";
																		
																		while ($level_5 = mysqli_fetch_assoc($level_5_query)){
																			
																			$arr[] = "<ul>";
																				//level 4 page selected has subcategories
																				if($level_5['category_href'].substr($page[1]."/".$page[2]."/".$page[3]."/".$page[4])){
																					$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_5['category_href']."/".$limit.">".$level_5['category_name']."</a></li>";	
																				}
																			$arr[] = "</ul>";
																		}
																	} else {
																		//level 4 page not selected
																		$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_4['category_href']."/".$limit.">".$level_4['category_name']."</a></li>";	
																	}	
																}
															$arr[] = "</ul>";
														}
													} else {
														//level 3 page not selected
														$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_3['category_href']."/".$limit.">".$level_3['category_name']."</a></li>";	
													}	
												}
											$arr[] = "</ul>";
										}
									} else {
										//level 2 page not selected
										$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_2['category_href']."/".$limit.">".$level_2['category_name']."</a></li>";	
									}
								}
							$arr[] = "</ul>";
						}
					} else {
						//level 1 page not selected
						$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_1['category_href']."/".$limit.">".$level_1['category_name']."</a></li>";		
					}
				}
			$arr[] = "</ul>";			

			return implode("\n", $arr);
		}
		
		//level 1-6 menu
		if($level == 6){
			$level_1_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `level_id` = 1 ORDER BY sort");
			
			$arr[] = "<ul>";
				while ($level_1 = mysqli_fetch_assoc($level_1_query)){
					$level_2_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_1['category_id']." ORDER BY sort");

					//level 1 page selected
					if($level_1['category_href'] == $page[1]){
						$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_1['category_href']."/".$limit."><b>".$level_1['category_name']."</b></a>";
						
						while ($level_2 = mysqli_fetch_assoc($level_2_query)){
							$arr[] = "<ul>";
								//level 1 page selected has subcategories
								if($level_2['category_href'].substr($page[1])){
									$level_3_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_2['category_id']." ORDER BY sort");

									//level 2 page selected
									if($level_2['category_href'] == $page[1]."/".$page[2]){		
										$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_2['category_href']."/".$limit."><b>".$level_2['category_name']."</b></a>";
										
										while ($level_3 = mysqli_fetch_assoc($level_3_query)){
											
											$arr[] = "<ul>";
												//level 2 page selected has subcategories
												if($level_3['category_href'].substr($page[1]."/".$page[2])){
													$level_4_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_3['category_id']." ORDER BY sort");

													//level 3 page selected
													if($level_3['category_href'] == $page[1]."/".$page[2]."/".$page[3]){		
														$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_3['category_href']."/".$limit."><b>".$level_3['category_name']."</b></a>";
														
														while ($level_4 = mysqli_fetch_assoc($level_4_query)){
															
															$arr[] = "<ul>";
																//level 3 page selected has subcategories
																if($level_4['category_href'].substr($page[1]."/".$page[2]."/".$page[3])){
																	$level_5_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_4['category_id']." ORDER BY sort");

																	//level 4 page selected
																	if($level_4['category_href'] == $page[1]."/".$page[2]."/".$page[3]."/".$page[4]){		
																		$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_4['category_href']."/".$limit."><b>".$level_4['category_name']."</b></a>";
																		
																		while ($level_5 = mysqli_fetch_assoc($level_5_query)){
																			
																			$arr[] = "<ul>";
																				//level 4 page selected has subcategories
																				if($level_5['category_href'].substr($page[1]."/".$page[2]."/".$page[3]."/".$page[4])){
																					$level_6_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_5['category_id']." ORDER BY sort");

																					//level 5 page selected
																					if($level_5['category_href'] == $page[1]."/".$page[2]."/".$page[3]."/".$page[4]."/".$page[5]){		
																						$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_5['category_href']."/".$limit."><b>".$level_5['category_name']."</b></a>";
																						
																						while ($level_6 = mysqli_fetch_assoc($level_6_query)){
																							
																							$arr[] = "<ul>";
																								//level 5 page selected has subcategories
																								if($level_6['category_href'].substr($page[1]."/".$page[2]."/".$page[3]."/".$page[4]."/".$page[5])){
																									$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_6['category_href']."/".$limit.">".$level_6['category_name']."</a></li>";	
																								}
																							$arr[] = "</ul>";
																						}
																					} else {
																						//level 5 page not selected
																						$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_5['category_href']."/".$limit.">".$level_5['category_name']."</a></li>";	
																					}
																				}
																			$arr[] = "</ul>";
																		}
																	} else {
																		//level 4 page not selected
																		$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_4['category_href']."/".$limit.">".$level_4['category_name']."</a></li>";	
																	}	
																}
															$arr[] = "</ul>";
														}
													} else {
														//level 3 page not selected
														$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_3['category_href']."/".$limit.">".$level_3['category_name']."</a></li>";	
													}	
												}
											$arr[] = "</ul>";
										}
									} else {
										//level 2 page not selected
										$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_2['category_href']."/".$limit.">".$level_2['category_name']."</a></li>";	
									}
								}
							$arr[] = "</ul>";
						}
					} else {
						//level 1 page not selected
						$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_1['category_href']."/".$limit.">".$level_1['category_name']."</a></li>";		
					}
				}
			$arr[] = "</ul>";			

			return implode("\n", $arr);
		}
		
		//level 1-7 menu
		if($level == 7){
			$level_1_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `level_id` = 1 ORDER BY sort");
			
			$arr[] = "<ul>";
				while ($level_1 = mysqli_fetch_assoc($level_1_query)){
					$level_2_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_1['category_id']." ORDER BY sort");

					//level 1 page selected
					if($level_1['category_href'] == $page[1]){
						$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_1['category_href']."/".$limit."><b>".$level_1['category_name']."</b></a>";
						
						while ($level_2 = mysqli_fetch_assoc($level_2_query)){
							$arr[] = "<ul>";
								//level 1 page selected has subcategories
								if($level_2['category_href'].substr($page[1])){
									$level_3_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_2['category_id']." ORDER BY sort");

									//level 2 page selected
									if($level_2['category_href'] == $page[1]."/".$page[2]){		
										$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_2['category_href']."/".$limit."><b>".$level_2['category_name']."</b></a>";
										
										while ($level_3 = mysqli_fetch_assoc($level_3_query)){
											
											$arr[] = "<ul>";
												//level 2 page selected has subcategories
												if($level_3['category_href'].substr($page[1]."/".$page[2])){
													$level_4_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_3['category_id']." ORDER BY sort");

													//level 3 page selected
													if($level_3['category_href'] == $page[1]."/".$page[2]."/".$page[3]){		
														$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_3['category_href']."/".$limit."><b>".$level_3['category_name']."</b></a>";
														
														while ($level_4 = mysqli_fetch_assoc($level_4_query)){
															
															$arr[] = "<ul>";
																//level 3 page selected has subcategories
																if($level_4['category_href'].substr($page[1]."/".$page[2]."/".$page[3])){
																	$level_5_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_4['category_id']." ORDER BY sort");

																	//level 4 page selected
																	if($level_4['category_href'] == $page[1]."/".$page[2]."/".$page[3]."/".$page[4]){		
																		$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_4['category_href']."/".$limit."><b>".$level_4['category_name']."</b></a>";
																		
																		while ($level_5 = mysqli_fetch_assoc($level_5_query)){
																			
																			$arr[] = "<ul>";
																				//level 4 page selected has subcategories
																				if($level_5['category_href'].substr($page[1]."/".$page[2]."/".$page[3]."/".$page[4])){
																					$level_6_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_5['category_id']." ORDER BY sort");

																					//level 5 page selected
																					if($level_5['category_href'] == $page[1]."/".$page[2]."/".$page[3]."/".$page[4]."/".$page[5]){		
																						$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_5['category_href']."/".$limit."><b>".$level_5['category_name']."</b></a>";
																						
																						while ($level_6 = mysqli_fetch_assoc($level_6_query)){
																							
																							$arr[] = "<ul>";
																								//level 5 page selected has subcategories
																								if($level_6['category_href'].substr($page[1]."/".$page[2]."/".$page[3]."/".$page[4]."/".$page[5])){
																									$level_7_query = $this->mysqlObject->query("SELECT * FROM "."`".$db."`"." WHERE `subcategory_id` = ".$level_6['category_id']." ORDER BY sort");
																									
																									//level 6 page selected
																									if($level_6['category_href'] == $page[1]."/".$page[2]."/".$page[3]."/".$page[4]."/".$page[5]."/".$page[6]){		
																										$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_6['category_href']."/".$limit."><b>".$level_6['category_name']."</b></a>";
																										
																										while ($level_7 = mysqli_fetch_assoc($level_7_query)){
																											
																											$arr[] = "<ul>";
																												//level 6 page selected has subcategories
																												if($level_7['category_href'].substr($page[1]."/".$page[2]."/".$page[3]."/".$page[4]."/".$page[5]."/".$page[6])){
																													$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_7['category_href']."/".$limit.">".$level_7['category_name']."</a></li>";	
																												}
																											$arr[] = "</ul>";
																										}
																									} else {
																										//level 6 page not selected
																										$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_6['category_href']."/".$limit.">".$level_6['category_name']."</a></li>";	
																									}
																								}
																							$arr[] = "</ul>";
																						}
																					} else {
																						//level 5 page not selected
																						$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_5['category_href']."/".$limit.">".$level_5['category_name']."</a></li>";	
																					}
																				}
																			$arr[] = "</ul>";
																		}
																	} else {
																		//level 4 page not selected
																		$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_4['category_href']."/".$limit.">".$level_4['category_name']."</a></li>";	
																	}	
																}
															$arr[] = "</ul>";
														}
													} else {
														//level 3 page not selected
														$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_3['category_href']."/".$limit.">".$level_3['category_name']."</a></li>";	
													}	
												}
											$arr[] = "</ul>";
										}
									} else {
										//level 2 page not selected
										$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_2['category_href']."/".$limit.">".$level_2['category_name']."</a></li>";	
									}
								}
							$arr[] = "</ul>";
						}
					} else {
						//level 1 page not selected
						$arr[] = "<li><div class='fin-categories-img'></div><a class='fin-transition-2' href=".'http://'.$_SERVER['SERVER_NAME']."/produkty/".$level_1['category_href']."/".$limit.">".$level_1['category_name']."</a></li>";		
					}
				}
			$arr[] = "</ul>";			

			return implode("\n", $arr);
		}
		
	}
	
	
	/**
	 * Generates show/hide menu levels 1-5
	 * @examlpe $menuGen->generateMenuAllLevels(PAGE);
	 *
	 * @param string
	 */ 
	public function generateMenuAllLevels($page){

		$page = explode('/', PAGE);
				
		switch($page){
			//menu level 2
			case 'produkty/'.$page[1].'/'.$page[2]:
				echo $this->generateMenuLevel("categories", 2, "0-10", PAGE);
			break;
			
			//menu level 3
			case 'produkty/'.$page[1].'/'.$page[2].'/'.$page[3]:
				echo $this->generateMenuLevel("categories", 3, "0-10", PAGE);
			break;
			
			//menu level 4
			case 'produkty/'.$page[1].'/'.$page[2].'/'.$page[3].'/'.$page[4]:
				echo $this->generateMenuLevel("categories", 4, "0-10", PAGE);
			break;
			
			//menu level 5
			case 'produkty/'.$page[1].'/'.$page[2].'/'.$page[3].'/'.$page[4].'/'.$page[5]:
				echo $this->generateMenuLevel("categories", 5, "0-10", PAGE);
			break;
			
			//menu level 6
			case 'produkty/'.$page[1].'/'.$page[2].'/'.$page[3].'/'.$page[4].'/'.$page[5].'/'.$page[6]:
				echo $menuGen->generateMenuLevel("categories", 6, "0-10", PAGE);
			break;
			
			//menu level 7
			case 'produkty/'.$page[1].'/'.$page[2].'/'.$page[3].'/'.$page[4].'/'.$page[5].'/'.$page[6].'/'.$page[7]:
				echo $menuGen->generateMenuLevel("categories", 7, "0-10", PAGE);
			break;
			
			//menu level 1
			default: echo $this->generateMenuLevel("categories", 1, "0-10", PAGE);
		}
	}	
}
?>