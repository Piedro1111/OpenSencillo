<?php
/**
 * Google Analytics for OpenSencillo
 * @name GOAS
 * @version 2015.005
 * @category Sencillo Library
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/licenses/gpl-3.0.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class analytics
{
	protected $arr;
	
	public function __construct()
	{
		$this->arr[] = "<script>";
		$this->arr[] = "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){";
		$this->arr[] = "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),";
		$this->arr[] = "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)";
		$this->arr[] = "})(window,document,'script','//www.google-analytics.com/analytics.js','ga');";
	}
	
	/**
	 * Add custom universal analytics
	 * @param $pref string (create|send|require)
	 * @param $value string (UA-XXXX-Y|pageview|...)
	 * @param $param string (auto|...)
	 */
	public function set($pref,$value,$param)
	{
		if(!empty($param))
		{
			$this->arr[] = "ga('$pref', '$value', '$param');";
		}
		else
		{
			$this->arr[] = "ga('$pref', '$value');";
		}
	}
	
	/**
	 * Set GA to default basic options
	 * @param $id string (UA-XXXX-Y)
	 */
	public function gadefault($id)
	{
		$this->arr[] = "ga('create', '$id', 'auto');";
		$this->arr[] = "ga('send', 'pageview');";
	}
	
	/**
	 * Create Google Analytics Code
	 */
	public function create()
	{
		$this->arr[] = "</script>";
		$this->arr = implode(PHP_EOL,$this->arr);
		return $this->arr;
	}
}
?>
