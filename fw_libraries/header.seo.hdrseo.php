<?php
/**
 * Main extend for file system
 * @name Sencillo Lib - headerSeo
 * @version 2015.109
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
	 * Block cache
	 */
	public function nocache()
	{
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		$this->header['cache-control-def'] = '<meta http-equiv="cache-control" content="no-cache">';
		$this->header['expires-def'] = '<meta http-equiv="expires" content="-1">';
		$this->header['pragma-def'] = '<meta http-equiv="pragma" content="no-cache">';
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
?>