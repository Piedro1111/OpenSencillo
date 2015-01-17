<?php
/*~ core_functions.php
.---------------------------------------------------------------------------.
|  Software: Sencillo Core                                                  |
|   Version: 2015.003                                                       |
|   Contact: ph@mastery.sk                                                  |
| ------------------------------------------------------------------------- |
|    Author: Bc. Peter Horváth (original founder)                           |
| Copyright (c) 2015, Bc. Peter Horváth. All Rights Reserved.               |
| ------------------------------------------------------------------------- |
|   License: Distributed under the General Public License (GPL)             |
|            http://www.gnu.org/copyleft/gpl.html                           |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'---------------------------------------------------------------------------'
~*/
/**
 * Main class for file system
 * @name Sencillo Core - fileSystem
 * @version 2014.008
 * @category core
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
 * @name Sencillo Core - file
 * @version 2014.008
 * @category core
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
 * Main extend for file system
 * @name Sencillo Core - headerSeo
 * @version 2015.002
 * @category core
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class headerSeo
{
	public $seo;
	public $info;

	private $header;
	private $body;
	private $lang;

	/**
	 * Create default status for page
	 */
	public function __construct()
	{
		$this->header['doctype-def']='<!DOCTYPE html>';
		$this->header['html-def']='<html><head>';
		$this->body='</head>';
	}
	
	/**
	 * Basic function for generate keywords tag
	 * @param string $kw add keywords
	 */
	public function keywords($kw)
	{
		$this->header['keywords-def'] = '<meta name="keywords" content="'.$kw.'" />';
	}
	
	/**
	 * Encoding webpage
	 * @param string $ec webpage encoding
	 */
	public function encode($ec='UTF-8')
	{
		$this->header['charset-def'] = '<meta charset='.$ec.'" />';
	}
	
	/**
	 * Enabled responsive page
	 */
	public function responsive()
	{
		$this->header['responsive-def'] = '<meta name="viewport" content="width=device-width, initial-scale=1">';
	}
	
	/**
	 * Page title
	 * @param string $t title size max 69 characters
	 */
	public function title($t)
	{
		if(strlen($t)>69)
		{
			$t = substr($t,0,66).'...';
		}
		$this->header['title-def'] = '<title>'.$t.'</title>';
	}
	
	/**
	 * Page description with max size 159 characters
	 * @param string $data
	 */
	public function description($data)
	{
		if(strlen($data)>159)
		{
			$data = substr($data,0,155).'...';
		}
		$this->header['description-def'] = '<meta name="description" content="'.$data.'">';
	}
	
	/**
	 * Add settings for Google Bot and Bing Bot
	 */
	public function robots()
	{
		$this->header['robots-def'] = '<meta name="ROBOTS" content="NOODP"><meta name="Slurp" content="NOYDIR">';
	}
	
	/**
	 * Add page owner meta tag
	 * @param string $author
	 */
	public function owner($author)
	{
		$this->header['owner-def'] = '<meta name="author" content="'.$author.'">';
	}
	
	/**
	 * Add page generator meta tag
	 */
	public function generator()
	{
		$this->header['generator-def'] = '<meta name="generator" content="OpenSencillo Framework (www.opensencillo.com)">';
	}
	
	/**
	 * Add custom code to header
	 * @param string $code
	 */
	public function custom($code)
	{
		$this->header['custom'][] = $code;
	}
	
	/**
	 * Add custom link to javascript
	 * @param string $code link
	 * @example headerSeo::script('//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js');
	 */
	public function script($code)
	{
		$this-custom('<script type="text/javascript" src="'.$code.'"></script>');
	}
	
	/**
	 * Save SEO and generate header content
	 * @return string
	 */
	public function save()
	{
		$this->seo = $this->header['doctype-def'];
		$this->seo .= $this->header['html-def'];
		$this->seo .= $this->header['charset-def'];
		$this->seo .= $this->header['responsive-def'];
		$this->seo .= $this->header['title-def'];
		$this->seo .= $this->header['description-def'];
		$this->generator();
		
		unset($this->header['html-def']);
		unset($this->header['doctype-def']);
		unset($this->header['charset-def']);
		unset($this->header['responsive-def']);
		unset($this->header['title-def']);
		unset($this->header['description-def']);
				
		foreach($this->header as $key => $val)
		{
			if(!is_array($val))
			{
				$this->seo .= $val;
				$this->info['head'][] = $key;
			}
			else 
			{
				foreach($this->header['custom'] as $key => $val)
				{
					$this->seo .= $val;
					$this->info['head'][] = $key;
				}
			}
		}
		
		$this->seo .= $this->body;
		return $this->seo;
	}
	
	/**
	 * Add lang attribute to html
	 * @param string
	 * @example headerSeo::lang('SK_sk');
	 * @example headerSeo::lang('SK');
	 */
	public function lang($lang)
	{
		unset($this->header['html-def']);
		$this->header['html-def']='<html lang="'.$lang.'"><head>';
	}
	
	/**
	 * Add google load call
	 */
	public function googleLoad()
	{
		$this->header['jquery-js']='<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>';
		$this->header['jqueryui-js']='<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>';
	}
	
	/**
	 * Add bootstrap call
	 */
	public function bootstrapDefs()
	{
		$this->header['bootstrap-css']='<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">';
		$this->header['jquery-js']='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>';
		$this->header['bootstrap-js']='<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>';
	}
}

/**
 * Core information
 * @name Sencillo Core - coreSencillo
 * @version 2015.002
 * @category core
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class coreSencillo
{
	public $info;
	private $authorized;
	private $pid;

	/**
	 * Add default information about Sencillo
	 */
	public function __construct()
	{
		$version = '2015';
		$layout	 = '0';
		$build	 = '03';
		$this->info=array(	'CMS'=>'OpenSencillo',
							'NME'=>'OpenSencillo',
							'VSN'=>$version.'.'.$layout.$build,
							'FWK'=>'OpenSencillo '.$version.'.'.$layout.$build,
							'ARN'=>'Bc.Peter Horváth, Mastery s.r.o. CEO and FOUNDER',
							'CPY'=>'(c)COPYRIGHT 2011-'.date('Y').' Bc.Peter Horváth',
							'HPE'=>'http://www.opensencillo.com',
							'DTC'=>'01.'.$build.'.'.$version.':00.00:00.'.$layout.$build,
							'PID'=>'PLEASE CONTACT info@opensencillo.com');
	}
	
	public function version_info()
	{
		return $this->info;
	}
	/**
	 * Run basic authentification script
	 * @param $domains array
	 * @example $this->authorized(array("www.example.com","example.com","test.example.com"))
	 */
	public function authorized($domains)
	{
		if(is_array($domains))
		{
			$this->authorized=$domains;
			foreach($this->authorized as $value) 
			{
				if($_SERVER['SERVER_NAME']==$value)
				{
					$this->pid=true;
					return $this->pid;
				}
				else
				{
					$this->pid=false;
					return $this->pid;
				}
			}
		}
	}
	
	public function __destruct()
	{
	}
}
$i=0;
$afterBootUp=array();
$afterBootUp[$i++]=new coreSencillo;
?>
