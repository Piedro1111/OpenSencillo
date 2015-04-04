<?php 
/**
 * Htaccess generator
 * @name htgen
 * @version 2015.002
 * @category Sencillo Library
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL3) http://www.gnu.org/licenses/gpl-3.0.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * @todo On build!!!
 */
class htgen
{
	protected $gen;
	/**
	 * HTACCESS Cache
	 * @param string mimetype
	 * @param int days
	 * @return array
	 */
	public function cache($type,$days)
	{
		$generator = '
# Sencillo HTACCESS Cache
<IfModule mod_expires.c>
	ExpiresActive on
';
		foreach($type as $key => $value)
		{
			$generator .= '
	ExpiresByType '.$value.' "access plus '.$days.' days"
';
		}
		$generator.='
</IfModule>
';
		$this->gen[0]=$generator;
		return array(0=>$generator);
	}
	
	/**
	 * Create mod_rewrite configuration
	 * @param string on|off
	 * @param string path example: /
	 * @param int page port
	 * @return array
	 */
	public function rewrite($status='on',$base='/',$port=443)
	{
		$generator = '
# Sencillo HTACCESS modrewrite configuration
RewriteCond %{SERVER_PORT} ^'.$port.'$
RewriteRule ^(.*)$ http://'.$_SERVER['SERVER_NAME'].'/$1 [L,R=301]

# Sencillo HTACCESS modrewrite URLs
RewriteEngine '.$status.'
RewriteBase '.$base.'
';
		$this->gen[1]=$generator;
		return array(1=>$generator);
	}
	
	/**
	 * Create pretty urls format
	 * @param string requested file name
	 * @param string url get variable
	 * @return array
	 */
	public function prettyUrl($file='index.php',$get='p')
	{
		$generator = '
# Sencillo HTACCESS pretty URLs format
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule ^(.*)$ '.$file.'?'.$get.'=$1 [L,QSA]
';
		$this->gen[2]=$generator;
		return array(2=>$generator);
	}
	
	/**
	 * Create relocation page opensencillo.com -> www.opensencillo.com
	 * @return array
	 */
	public function toWww()
	{
		$generator = '
# Sencillo HTACCESS opensencillo.com -> www.opensencillo.com
RewriteCond %{HTTP_HOST} !^'.$_SERVER['SERVER_NAME'].'$ [NC]
RewriteRule ^(.*)$ http://'.$_SERVER['SERVER_NAME'].'/$1 [L,R=301]
';
		$this->gen[3]=$generator;
		return array(3=>$generator);
	}
	
	/**
	 * Hide htaccess
	 * @return array
	 */
	public function preventView()
	{
		$generator = '
# Sencillo HTACCESS Prevent viewing of .htaccess file
<Files .htaccess>
	order allow,deny
	deny from all
</Files>
';
		$this->gen[4]=$generator;
		return array(4=>$generator);
	}
	
	/**
	 * Prevent directory listings
	 * @return array
	 */
	public function preventDir()
	{
		$generator = '
# Sencillo HTACCESS Prevent directory listings
Options All -Indexes
';
		$this->gen[5]=$generator;
		return array(5=>$generator);
	}
	
	/**
	 * Change default directory page
	 * @param string
	 * @return array
	 */
	public function directory($dir='index.php')
	{
		$generator = '
# Sencillo HTACCESS Change default directory page
DirectoryIndex '.$dir.'
';
		$this->gen[6]=$generator;
		return array(6=>$generator);
	}
	
	/**
	 * Error pages path
	 * @param array error pages paths
	 * @return array
	 */
	public function errorPages($errpages)
	{
		$generator = array();
		foreach($errpages as $key=>$val)
		{
			$generator[] ='# Sencillo HTACCESS Custom '.$key.' errors';
			$generator[] ='ErrorDocument '.$key.' '.$val.'';
		}
		$generator = implode(PHP_EOL,$generator);
		$this->gen[7]=$generator;
		return array(7=>$generator);
	}
	
	/**
	 * Block/Allow users by IP
	 * @param array banned ip
	 * @param array allowed ip
	 * @return array
	 */
	public function perm($banlist,$allowlist=null)
	{
		$generator = '
# Block users by IP
order allow,deny
';
		foreach($banlist as $key => $value)
		{
			$generator .= '
	deny from '.$value.'
';
		}
		foreach($allowlist as $key => $value)
		{
			$generator .= '
	allow from '.$value.'
';
		}
		$this->gen[8]=$generator;
		return array(8=>$generator);
	}
	
	/**
	 * Switch generator to write noarray output mode
	 * @return string
	 */
	public function prepare()
	{
		$this->gen=asort($this->gen);
		foreach($this->gen as $key=>$val)
		{
			$generator.=$val;
		}
		return $generator;
	}
	
	/**
	 * Default htaccess configuration
	 */
	public function installerScheme()
	{
		self::cache(array('image/jpg','image/jpeg','image/gif','image/png'),30);
		self::rewrite();
		self::prettyUrl();
		self::toWww();
		return self::prepare();
	}
}
?>