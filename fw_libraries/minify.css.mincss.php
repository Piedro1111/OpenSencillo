<?php
/**
 * @name On-the-fly CSS Compression
 * @author Copyright (c) 2009 and onwards, Manas Tungare.
 * @author edited for OpenSencillo by Bc. Peter Horváth
 *
 * In order to minimize the number and size of HTTP requests for CSS content,
 * this script combines multiple CSS files into a single file and compresses
 * it on-the-fly.
 *
 * @tutorial To use this in your HTML, link to it in the usual way:
 * @tutorial <link rel="stylesheet" type="text/css" media="screen, print, projection" href="/css/compressed.css.php" />
 */
class css
{
	/* Add your CSS files to this array (THESE ARE ONLY EXAMPLES) */
	protected $cssFiles = array();
	
	/**
	 * Set css relative path (one css file only)
	 * @example $this->add('/css/style.css');
	 * @param string $path
	 */
	final public function add($path)
	{
		$this->cssFiles[] = $path;
	}
	/**
	 * Run minify process
	 * Ideally, you wouldn't need to change any code beyond this point.
	 * @return string minify css
	 */
	final public function minify()
	{
		$buffer = "";
		foreach ($this->cssFiles as $cssFile) {
		  $buffer .= file_get_contents($cssFile);
		}
		
		// Remove comments
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		
		// Remove space after colons
		$buffer = str_replace(': ', ':', $buffer);
		
		// Remove whitespace
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buffer);
		
		// Collapse adjacent spaces into a single space
		$buffer = ereg_replace(" {2,}", ' ',$buffer);
		
		// Remove spaces that might still be left where we know they aren't needed
		$buffer = str_replace(array('} '), '}', $buffer);
		$buffer = str_replace(array('{ '), '{', $buffer);
		$buffer = str_replace(array('; '), ';', $buffer);
		$buffer = str_replace(array(', '), ',', $buffer);
		$buffer = str_replace(array(' }'), '}', $buffer);
		$buffer = str_replace(array(' {'), '{', $buffer);
		$buffer = str_replace(array(' ;'), ';', $buffer);
		$buffer = str_replace(array(' ,'), ',', $buffer);
		
		// Enable GZip encoding.
		ob_start("ob_gzhandler");
		
		// Enable caching
		header('Cache-Control: public');
		
		// Expire in one day
		header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
		
		// Set the correct MIME type, because Apache won't set it for us
		header("Content-type: text/css");
		
		// Write everything out
		return $buffer;
	}
	
	/**
	 * Call minify css
	 * @return string
	 */
	final public function call()
	{
		return '<link rel="stylesheet" type="text/css" media="screen, print, projection" href="/css/compressed.css.php" />';
	}
}
?>