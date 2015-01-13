<?php
/**
 * Simple file upload
 * @name upload
 * @version 2015.002
 * @category Sencillo Library
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class upload
{
	protected $mime;
	protected $uploadDirectory;
	protected $size;
	protected $uploadInfo;
	protected $mode;
	protected $status;
	
	public function __construct($path)
	{
		$this->mime=null;
		$this->path($path);
		$this->advancedMode();
	}
	
	/**
	 * Internal protected function for detection legal mime type
	 * 
	 * @param mixed $mime
	 */
	protected function mimeTest($mime)
	{
		if($val == $_FILES['FileInput']['type'])
		{
			$this->mime=true;
		}
	}
	
	/**
	 * Config method for legal mime types
	 * 
	 * @param string $mime
	 */
	public function mimeConfig($mime=null)
	{
		if(is_array($mime))
		{
			foreach($mime as $val)
			{
				$this->mimeTest($val);
			}
		}
		else 
		{
			$this->mimeTest($mime);
		}
		
		/**
		 * default, if $mime is null
		 */
		if($mime==null)
		{
			$this->mime=true;
		}
	}
	
	/**
	 * specify upload directory ends with / (slash)
	 * 
	 * @param string $path
	 */
	public function path($path)
	{
		$this->uploadDirectory = $path;//example '/home/website/file_upload/uploads/'
	}
	
	/**
	 * Maximum allowed file size
	 * 
	 * @param int $size
	 */
	public function maxSize($size)
	{
		$this->size = $size;
	}
	
	/**
	 * Advanced upload mode TRUE/FALSE, deafult FALSE
	 * 
	 * @param bool $mode
	 */
	public function advancedMode($mode=null)
	{
		switch($mode)
		{
			case true:
				$this->mode = true;
			break;
			default:
				$this->mode = false;
			break;
		}
	}
	
	/**
	 * Json status encoder for AJAX comunication
	 */
	final public function ajaxSendJson($respond=null)
	{
		if($respond==null)
		{
			print json_encode($this->status);
		}
		else
		{
			print json_encode($this->status[$respond]);
		}
	}
	
	/**
	 * Get new file name
	 */
	public function name()
	{
		return $this->uploadInfo['newName'];
	}
	
	/**
	 * Upload method
	 * 
	 * 200   - OK
	 * 404   - Not found / Unknown error / No file
	 * 413   - Request is to large (maximum size is low)
	 * 415-0 - Unsupported Media Type (acquired in upload method)
	 * 415-1 - Unsupported Media Type (acquired in mimeConfig method)
	 * 417   - Expectation Failed (failed moving file from temporary location)
	 * 444   - No Response / Internal AJAX fail
	 */
	public function upload()
	{
		$this->status = array();
		if(isset($_FILES["FileInput"])/* && $_FILES["FileInput"]["error"]== UPLOAD_ERR_OK*/)
		{
			$UploadDirectory = $this->uploadDirectory; //specify upload directory ends with / (slash)
			 
			/*
			 Note : You will run into errors or blank page if "memory_limit" or "upload_max_filesize" is set to low in "php.ini".
			 Open "php.ini" file, and search for "memory_limit" or "upload_max_filesize" limit
			 and set them adequately, also check "post_max_size".
			 */
			 
			//check if this is an ajax request
			if($this->mode)
			{
				$this->status['mode']='advanced';
				if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']))
				{
					$this->status['code']="444";
				}
				 
				//Is file size is less than allowed size.
				if ($_FILES["FileInput"]["size"] > $this->size) 
				{
					$this->status['code']="413";
				}
				 
				//allowed file type Server side check
				if($this->mime==true)
				{
					switch(strtolower($_FILES['FileInput']['type']))
					{
						//allowed file types
						case 'image/png':
						case 'image/gif':
						case 'image/jpeg':
						case 'image/pjpeg':
						case 'text/plain':
						case 'text/html': //html file
						case 'application/x-zip-compressed':
						case 'application/pdf':
						case 'application/msword':
						case 'application/vnd.ms-excel':
						case 'video/mp4':
							$this->status['mime']=$_FILES['FileInput']['type'];
							break;
						default:
							$this->status['code']="415-0"; //output error
					}
				}
				else 
				{
					$this->status['code']="415-1";//output error
				}
			}
			else
			{
				$this->status['mode']='simple';
			}
			 
			$File_Name          = strtolower($_FILES['FileInput']['name']);
			$File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
			$Random_Number      = rand(0, 9999999999).date('YmdHis'); //Random number to be added to name.
			$NewFileName        = $Random_Number.$File_Ext; //new file name
			
			$this->uploadInfo = array('oldName'=>$File_Name,
									  'ext'=>$File_Ext,
									  'rnd'=>$Random_Number,
									  'newName'=>$NewFileName);
			$this->status['info'] = $this->uploadInfo;
			
			if(!is_dir($UploadDirectory))
			{
			    mkdir($UploadDirectory,0777);
			}
			if(move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory.$NewFileName))
			{
				// do other stuff
				$this->status['code']="200";
				$this->status['name']=$NewFileName;
			}
			else
			{
				$this->status['code']="417";
			}
			
		}
		else
		{
			$this->status['code']="404";
		}
		
		if($this->status['code']=='200')
		{
		    $this->ajaxSendJson('name');
		}
		else
		{
		    $this->ajaxSendJson('code');
		}
	}
}
?>