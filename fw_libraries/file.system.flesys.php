<?php
/**
 * Main class for file system
 * @name Sencillo Lib - fileSystem
 * @version 2015.109
 * @category libraries
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class fileSystem
{
	public $name;

	private $rfp;
	private $wfp;
	private $contents;

	/**
	 * fileSystem constructor - create object for file manipulation
	 * @param string $name
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}
	/**
	 * Write data to file
	 * @param string $data to write
	 */
	final public function write($data)
	{
		$this->wfp = fopen($this->name,"wb");
		fwrite($this->wfp,$data);
		fclose($this->wfp);
	}
	/**
	 * Read file from file
	 * @return string
	 */
	final public function read()
	{
		$this->rfp = fopen($this->name,"rb");
		$this->contents = '';
		while (!feof($this->rfp))
		{
			$this->contents .= fread($this->rfp, 8192);
		}
		fclose($this->rfp);
		return $this->contents;
	}
}
/**
 * Main extend for file system
 * @name Sencillo Lib - file
 * @version 2014.008
 * @category librariries
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class file extends fileSystem
{
    /**
     * Switch folders chmod to 0777
     * @param string $name
     */
	public function __construct($name)
	{
		chmod("../fw_core/", 0777);
    	chmod("../fw_cache/", 0777);
    	chmod("../fw_headers/", 0777);
    	chmod("../fw_modules/", 0777);
    	chmod("../fw_libraries/", 0777);
    	chmod("../fw_script/", 0777);
    	chmod("../", 0777);
	}
	/**
	 * Switch folders chmod to 0700
	 * @param string $name
	 */
	public function __destruct()
	{
		chmod("../fw_core/", 0700);
    	chmod("../fw_cache/", 0700);
    	chmod("../fw_headers/", 0700);
    	chmod("../fw_modules/", 0700);
    	chmod("../fw_libraries/", 0700);
    	chmod("../fw_script/", 0700);
    	chmod("../", 0700);
	}
}

/**
 * Convert data
 * @name Sencillo Lib - Convert
 * @version 2015.109
 * @category core
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class convert
{
	/**
	 * Html to text
	 * @param string $html
	 * @return string without html
	 */
	public function stripHtml($html)
	{
		$blockTags = '/?p|/?h\\d|li|dt|br|hr|/tr';
		$text = $html;
		$text = preg_replace('~<!--.*-->~sU', '', $text);
		$text = preg_replace('~<(script|style|head).*</\\1>~isU', '', $text);
		$text = preg_replace('~<(td|th|dd)[ >]~isU', ' \\0', $text);
		$text = preg_replace('~\\d+~u', ' ', $text);
		$text = preg_replace('~<($blockTags)[ >/]~i', '\n\\0', $text);
		$text = strip_tags($text);
		$text = html_entity_decode($text, ENT_QUOTES, "utf-8");
		
		return $text;
	}
}
?>