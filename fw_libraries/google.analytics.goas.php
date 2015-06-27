<?php
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
		$this->gadefault('UA-50042410-7');
		$this->create();
	}
	
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
	
	public function gadefault($id)
	{
		$this->arr[] = "ga('create', '$id', 'auto');";
		$this->arr[] = "ga('send', 'pageview');";
	}
	
	public function create()
	{
		$this->arr[] = "</script>";
		$this->arr = implode(PHP_EOL,$this->arr);
	}
}
?>
