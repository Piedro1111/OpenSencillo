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
	ExpiresByType '.$type.' "access plus '.$days.' days"
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
# opensencillo.com -> www.opensencillo.com
RewriteCond %{HTTP_HOST} !^'.$_SERVER['SERVER_NAME'].'$ [NC]
RewriteRule ^(.*)$ http://'.$_SERVER['SERVER_NAME'].'/$1 [L,R=301]
';
		$this->gen[3]=$generator;
		return array(3=>$generator);
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
	
	public function installerScheme()
	{
		self::cache(array('image/jpg','image/jpeg','image/gif','image/png'),30);
		self::rewrite();
		self::prettyUrl();
		self::toWww();
	}
}
?>