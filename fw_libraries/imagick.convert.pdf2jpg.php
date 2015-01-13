<?php
/**
 * Simple transform PDF to JPG file
 * @name PDFtoJPG
 * @version 2015.002
 * @category Sencillo Library
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class PDFtoJPG
{
	public $error=array();
	public $path=array();
	private $j;
	
	/**
	 * Convert PDF to JPG via PHP imagick
	 * 
	 * @param string $name (name input file)
	 * @param number $id (numeric user id if system using id) 
	 * @param string $pdfSource (pdf source path)
	 * @param string $jpgOutDir (jpg out root dir path)
	 *  
	 * @return number
	 */
	public function convert($name,$id=null,$pdfSource=null,$jpgOutDir=null,$quality=null)
	{
	    ignore_user_abort(true);
	    $id = ($id === null ? 'Guests' : $id);
	    $pdfSource = ($pdfSource === null ? './' : $pdfSource);
	    $jpgOutDir = ($jpgOutDir === null ? './' : $jpgOutDir);
	    $quality = ($quality === null ? 100 : $quality);
	    
		$imagick = new Imagick();
		chmod($pdfSource,0777);
		chmod($jpgOutDir,0777);
		//rmdir($jpgOutDir."/user$id");
		mkdir($jpgOutDir."/user$id",0777);
		rmdir($jpgOutDir."/user$id/$name");
		mkdir($jpgOutDir."/user$id/$name",0777);
		chmod($jpgOutDir."/user$id/",0777);
		chmod($jpgOutDir."/user$id/$name/",0777);

		$i=0;
		$this->j=1;
		while($i<1000)
		{
			$this->outPath($jpgOutDir."/user".$id."/".$name."/".$name."[".$i."].jpg");
			try
			{
				$imagick->setResolution(144,144);
				$imagick->readImage($this->inPath($pdfSource."/user".$id."/".$name."[".$i."]"));
				//$imagick->setCompression(Imagick::COMPRESSION_JPEG);
				$imagick->setImageCompressionQuality($quality);
				//$resolution=$imagick->getImageResolution();
				//$imagick->scaleImage(0,1000);
				
				//$imagick->setImageDepth(600);
				$imagick->writeImage($this->outPath($jpgOutDir."/user".$id."/".$name."/".$name."[".$i."].jpg"));
				$this->j++;
			}
			catch(Exception $e)
			{
				$this->error[$i] = $e;
				$this->error['message'] = 'Imagick Exception: '.$e;
				break;
			}
			$i++;
		}
		return $this->j;
	}
	
	/**
	 * Convert simulation 
	 * 
	 * @param string $name (name input file)
	 * @param number $id (numeric user id if system using id) 
	 * @param string $pdfSource (pdf source path)
	 * 
	 * @return mixed
	 */
	public function simulation($name,$id=null,$pdfSource=null) 
	{ 
		$id = ($id === null ? 'Guests' : $id);
		$pdfSource = ($pdfSource === null ? './' : $pdfSource);
		 
		$imagick = new Imagick();	
		
		$i=0;
		$this->j=1;
		while($i<1000)
		{
			try
			{
				$imagick->readImage($this->inPath($pdfSource."/user".$id."/".$name."[".$i."]"));
				$this->j++;
			}
			catch(Exception $e)
			{
				break;
			}
			$i++;
		}
		return array('maxPage'=>$this->j,
					 'minPage'=>0,
					 'source'=>$pdfSource."/user".$id."/".$name);
	}
	
	/**
	 * Convert simulation for generate path to source.
	 * Without ImageMagic php extension.
	 *
	 * @param string $name (name input file)
	 * @param number $id (numeric user id if system using id)
	 * @param string $pdfSource (pdf source path)
	 *
	 * @return mixed
	 */
	public function pdfPathGenerator($name,$id=null,$pdfSource=null)
	{
		$id = ($id === null ? 'Guests' : $id);
		$pdfSource = ($pdfSource === null ? './' : $pdfSource);
		
		return array('maxPage'=>'unknown',
					 'minPage'=>'unknown',
					 'source'=>$pdfSource."/user".$id."/".$name);
	}
	/**
	 * Get last error
	 */
	public function errorList()
	{
		return $this->error;
	}
	
	/**
	 * Path finder for testing
	 * @param string
	 */
	public function inPath($path)
	{
	    $this->path['in'] = $path;
		return $path;
	}
	
	/**
	 * Path finder for testing
	 * @param string
	 */
	public function outPath($path)
	{
	    $this->path['out'] = $path;
	    return $path;
	}
}
?>
