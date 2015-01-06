<?php 
class url
{
	protected $data;
	
	public function __construct()
	{
		$this->data['content']=$_GET['p'];
		$this->data['hash']=md5($data.date("YmdHis"));
	}
	
	/**
	 * Get url
	 * @param bool
	 * @return mixed
	 */
	public function url($array=false)
	{
		return ($array===true?$this->data['content']:"//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	}
}
?>