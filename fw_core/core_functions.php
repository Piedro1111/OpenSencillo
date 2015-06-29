<?php
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
 * @version 2015.004
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
	private $oginfo;

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
		$this->header['charset-def'] = '<meta charset="'.$ec.'" />';
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
		$this->oginfo['title'] = $t;
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
		$this->oginfo['description'] = $data;
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
		$this->custom('<script type="text/javascript" src="'.$code.'"></script>');
	}
	
	/**
	 * Save SEO and generate header content
	 * @return string
	 */
	public function save()
	{
		$this->seo = $this->header['doctype-def'].PHP_EOL;
		$this->seo .= (isset($this->header['html-def-snippet'])?$this->header['html-def-snippet']:$this->header['html-def']).PHP_EOL;
		$this->seo .= $this->header['charset-def'].PHP_EOL;
		$this->seo .= $this->header['responsive-def'].PHP_EOL;
		$this->seo .= $this->header['title-def'].PHP_EOL;
		$this->seo .= $this->header['description-def'].PHP_EOL;
		$this->generator();
		
		unset($this->header['html-def']);
		unset($this->header['html-def-snippet']);
		unset($this->header['doctype-def']);
		unset($this->header['charset-def']);
		unset($this->header['responsive-def']);
		unset($this->header['title-def']);
		unset($this->header['description-def']);
				
		foreach($this->header as $key => $val)
		{
			if(!is_array($val))
			{
				$this->seo .= $val.PHP_EOL;
				$this->info['head'][] = $key;
			}
		}
		foreach($this->header['custom'] as $key => $val)
		{
			$this->seo .= $val.PHP_EOL;
			$this->info['head'][] = $key;
		}
		
		$this->seo .= $this->body;
		return $this->seo;
	}
	
	/**
	 * Add lang attribute to html
	 * @param string
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
	 * Add jquery
	 */
	public function jquery()
	{
		self::googleLoad();
	}
	
	/**
	 * Add og-tags and social tags
	 * @param array
	 */
	public function socialTags($arr, $snippet=false)
	{
		$this->custom('<meta property="og:url" content="'.$arr['url'].'" />');
		$this->custom('<meta property="og:type" content="'.$arr['type'].'" />');
		$this->custom('<meta property="og:title" content="'.$this->oginfo['title'].'" />');
		$this->custom('<meta property="og:description" content="'.$this->oginfo['description'].'" />');
		$this->custom('<meta property="og:image" content="'.$arr['image'].'" />');
		
		if($snippet)
		{
			$this->header['html-def-snippet'] = '<html itemscope itemtype="http://schema.org/Other"><head>';
			
			$this->custom('<meta itemprop="name" content="'.$this->oginfo['title'].'">');
			$this->custom('<meta itemprop="description" content="'.$this->oginfo['description'].'">');
			$this->custom('<meta itemprop="image" content="'.$arr['image'].'">');
		}
	}
	
	/**
	 * Add bootstrap call
	 */
	public function bootstrapDefs()
	{
		$this->header['bootstrap-css']='<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">';
		$this->header['jquery-js']='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>';
		$this->header['bootstrap-js']='<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>';
	}
}

/**
 * Core information
 * @name Sencillo Core - coreSencillo
 * @version 2015.005
 * @category core
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class coreSencillo implements coreInterface
{
	public $info;
	public $request;
	public $original_request;
	public $post;
	public $get;
	
	private $authorized;
	private $pid;

	/**
	 * Add default information about Sencillo
	 */
	public function __construct($sum=null)
	{
		$version = '2015';
		$layout	 = '1';
		$build	 = '05';
		$this->info=array(	'CMS'=>'OpenSencillo',
							'NME'=>'OpenSencillo',
							'VSN'=>$version.'.'.$layout.$build,
							'FWK'=>'OpenSencillo '.$version.'.'.$layout.$build,
							'ARN'=>'Bc.Peter Horváth, Mastery s.r.o. CEO and FOUNDER',
							'CPY'=>'&copy; COPYRIGHT 2011-'.date('Y').' Bc.Peter Horváth',
							'HPE'=>'http://www.opensencillo.com',
							'DTC'=>'01.'.$build.'.'.$version.':00.00:00.'.$layout.$build,
							'PID'=>'PLEASE CONTACT info@opensencillo.com',
							'SUM'=>$sum);
		
		$this->io_validator();
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
					$this->pid[$value]=true;
				}
				else
				{
					$this->pid[$value]=false;
				}
			}
		}
	}
	
	/**
	 * Check product key
	 */
	public function product($path=false)
	{
		if($path==false)
		{
			$read = new fileSystem('http://auth.mastery.sk/action.php');
		}
		else
		{
			$read = new fileSystem('key.pid');
		}
		$exist= fopen($read->name,"rb");
		if(!$exist)
		{
			die($this->info['PID']);
		}
		else
		{
			return $read->read();
		}
	}
	
	/**
	 * Check product key
	 */
	public function payLock()
	{
		if(!file_exists("key.pid"))
		{
			$json=json_decode(self::product(false),true);
			$this->authorized($json['domains']);
			if($this->pid[$_SERVER['SERVER_NAME']]!==true)
			{
				die($this->info['PID']);
			}
			$this->info['product']=$json;
			if(($json['sum']!='none')&&(!empty($this->info['SUM']))&&($json['sum']==$this->info['SUM']))
			{
				$write = new fileSystem('key.pid');
				$json['expired']=md5(date('Ym'));
				$write->write(json_encode($json));
			}
		}
		else
		{
			$json=json_decode(self::product(true),true);
			$this->authorized($json['domains']);
			if(($this->pid[$_SERVER['SERVER_NAME']]!==true)&&($json['sum']===$this->info['SUM']))
			{
				die($this->info['PID']);
			}
			$this->info['product']=$json;
			if($this->info['product']['expired']!=md5(date('Ym')))
			{
				unlink('key.pid');
			}
		}
	}
	
	/**
	 * Rewrite inputs (REQUEST, POST, GET)
	 * @param $input array
	 * @return array
	 */
	private function io_rw($input)
	{
		$arr=array();
		
		foreach($input as $key=>$val)
		{
			if(is_string($val))
			{
				$arr[$key]=htmlspecialchars($val,ENT_COMPAT | ENT_HTML5);
				$arr['admin_original'][$key]=$val;
			}
		}
		
		return $arr;
	}
	
	/**
	 * Add validation data to output and call rewrite inputs
	 * @see io_rw($input)
	 * @return bool
	 */
	private function io_validator()
	{
		$arr = array(
					'request'=>$this->io_rw($_REQUEST),
					'get'=>$this->io_rw($_GET),
					'post'=>$this->io_rw($_POST)
					);
		
		$arr['get']['core_info']	=	$this->info;
		$arr['request']['core_info']=	$this->info;
		$arr['post']['core_info']	=	$this->info;
		$arr['request']['status']	=	200;
		$arr['get']['status']		=	200;
		$arr['post']['status']		=	200;
		
		$_GET		=	$arr['get'];
		$_POST		=	$arr['post'];
		$_REQUEST	=	$arr['request'];
		
		$this->get		=	$arr['get'];
		$this->post		=	$arr['post'];
		$this->request	=	$arr['request'];
		
		if(($_GET['status']==200)&&($_POST['status']==200)&&($_REQUEST['status']==200))
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	/**
	 * Add basic sencillo upgrade function
	 * @example $this->upgrade('http://upgrade.opensencillo.com/source/');
	 * @param $source string
	 */
	public function upgrade($source=null)
	{
		$fileList=scandir('./fw_core');
		
		foreach($fileList as $key=>$val)
		{
			if($key>1)
			{
				$md5 = md5_file('./fw_core/'.$val);
				$remote_md5 = md5_file($source.$val.'.suf');
				if($md5!=$remote_md5)
				{
					$read = new fileSystem($source.$val.'.suf');
					$write= new fileSystem('./fw_core/'.$val);
					$write->write($read->read());
				}
			}
		}
	}
	
	public function __destruct()
	{
	}
}

/**
 * Core boot up sequention
 * @name Sencillo Core - bootUp
 * @version 2015.005
 * @category core
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * @example $boot=new bootUp(); //free code
 * @example $boot=new bootUp(true); //pay code
 */
class bootUp
{
	public $coreSencillo;
	public $headerSeo;
	public $fileSystem;
	
	public function __construct($sum=false)
	{
		$this->headerSeo	= new headerSeo;
		$this->fileSystem	= new fileSystem('firststart.json');
		
		if($sum)
		{
			$this->coreSencillo = new coreSencillo(json_decode($this->fileSystem->read(),true));
			$this->fileSystem->payLock();
		}
		else
		{
			$this->coreSencillo = new coreSencillo;
		}
	}
}
$core = new bootUp(false);
?>
