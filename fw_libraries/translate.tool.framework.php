<?php
/**
 * Translating web page via json UTF-8 array
 * 
 * @example {"key":{"en":"key","sk":"kluc"}}
 * @author Peter Horváth
 */
class translate extends fileSystem
{
	protected $tSource;
	protected $lang;
	
	public function __construct($name,$lang)
	{
		$this->name = $name;
		$this->lang = $lang;
		
		if(file_exists($this->name))
		{
			$this->tSource = json_decode($this->read(),true);	
		}
	}
	
	/**
	 * Find translate by key
	 * @param string $tKey
	 * @return bool
	 */
	final public function translate($tKey)
	{
		return ($this->tSource[$tKey][$this->lang] ? $this->tSource[$tKey][$this->lang] : $tKey);
	}
	
	/**
	 * Add translate key and translate data
	 * @example $this->addTranslate("key",array("en"=>"key","sk"=>"kľúč"));
	 * @param string $tKey
	 * @param array $tData
	 * @return boolean
	 */
	final public function addTranslate($tKey,$tData)
	{
		if((is_string($tKey))&&(is_array($tData)))
		{
			$this->tSource[$tKey]=$tData;
			$this->write(json_encode($this->tSource));
			return true;
		}
		else 
		{
			return false;
		}
	}
}
?>