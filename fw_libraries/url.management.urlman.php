<?php 
/**
 * Url extension
 * @todo continue creating new method for manipulate with URL
 * @name url
 * @version 2017.104
 * @category Sencillo Library
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/licenses/gpl-3.0.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class url
{
	protected $data;
	
	public function __construct()
	{
		$this->data['hash']=md5($data.date("YmdHis"));
	}
	
	/**
	 * Get url/urls
	 * @param bool
	 * @return mixed
	 */
	public function url($array=false)
	{
		return ($array===true?$this->data['content']:"//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	}
	
	/**
	 * Add url
	 * @param string relative page url
	 * @param string file template url
	 */
	public function addUrl($url,$template)
	{
		$this->data['content'][$url]=$template;
	}
	
	/**
	 * Remove url
	 * @param string
	 */
	public function removeUrl($url)
	{
		unset($this->data['content'][$url]);
		return $url;
	}
	
	/**
	 * Get page by PAGE
	 * @page string PAGE
	 */
	public function getPage($page)
	{
		return $this->data['content'][$page];
	}
	
	/**
	 * Breadcrumb select
	 * @param string from PAGE
	 * @param array('ab/cd/ef'=>'Ef page')
	 * 
	 * @tutorial $this->breadcrumb(PAGE,array('first'=>'My first page','first/second'=>'My second page','first/second/third'=>'My third page'));
	 * 
	 * @return array(0=>'Page name 0',1=>'Page name 1' ...)
	 */
	public function breadcrumb($page,$pageNames)
	{
		$page = explode('/',$page);
		foreach($page as $key=>$val)
		{
			$bcrumb.='/'.$val;
			$arr[]=$pageNames[$bcrumb];
		}
		return $arr[$page];
	}
}
?>