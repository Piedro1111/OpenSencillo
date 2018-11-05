<?php
/**
 * Simple file upload
 * @name upload
 * @version 2017.104
 * @category Sencillo Library
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/licenses/gpl-3.0.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class upload
{
	public $mime;
	protected $uploadDirectory;
	protected $size;
	protected $uploadInfo;
	protected $mode;
	protected $status;
    public $ajax = false;
	
	public function __construct($path)
	{
		$this->mime=null;
		$this->path($path);
		$this->advancedMode();
	}
	
	/**
	 * Internal protected function for detection legal mime type
	 * 
     * @deprecated since version 2015.002
	 * @param mixed $mime
	 */
	protected function mimeTest($mime)
	{
		if(($mime == $_FILES['file']['type'])&&(isset($mime)&&(!empty($mime))))
		{
			$this->mime=true;
		}
	}
	
	/**
	 * Config method for legal mime types
	 * 
     * @deprecated since version 2015.002
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
		if($mime===null)
		{
			$this->mime=true;
		}
	}
    
    /**
	 * Set legal mime types
	 * 
	 * @param array $mime
	 */
	public function setMimes($mime=null)
	{
		if(is_array($mime))
		{
			$this->mime=$mime;
		}
        else
        {
            die(__CLASS__.':'.__METHOD__.':line='.__LINE__);
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
			return json_encode($this->status);
		}
		else
		{
			return json_encode($this->status[$respond]);
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
	 * 415-1 - Unsupported Media Type (acquired in mimeConfig method) [depecrated]
	 * 417   - Expectation Failed (failed moving file from temporary location)
	 * 444   - No Response / Internal AJAX fail
	 */
	public function upload()
	{
		$this->status = array();
		if(isset($_FILES["file"])/* && $_FILES["file"]["error"]== UPLOAD_ERR_OK*/)
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
                if($this->ajax===true)
                {
                    $this->status['ajax']=true;
                    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']))
                    {
                        $this->status['code']="444";
                        return $this->status;
                    }
                }
                else
                {
                    $this->status['ajax']=false;
                }
                
				//Is file size is less than allowed size.
				if ($_FILES["file"]["size"] > $this->size) 
				{
					$this->status['code']="413";
                    return $this->status;
				}
				 
				//allowed file type Server side check
                if(in_array(strtolower($_FILES['file']['type']),$this->mime)===true)
                {
                    $this->status['mime']=$_FILES['file']['type'];
                }
                else
                {
                    $this->status['code']="415-0"; //output error
                    return $this->status;
                }
			}
			else
			{
				$this->status['mode']='simple';
			}
			 
			$File_Name          = strtolower($_FILES['file']['name']);
			$File_Name_EXP      = explode('.',$File_Name);
			$File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
			$Random_Number      = date('YmdHis'); //Random number to be added to name.
			$NewFileName        = $File_Name_EXP[0].'-'.$Random_Number.$File_Ext; //new file name
			
			$this->uploadInfo = array('oldName'=>$File_Name,
									  'ext'=>$File_Ext,
									  'rnd'=>$Random_Number,
									  'newName'=>$NewFileName);
			$this->status['info'] = $this->uploadInfo;
			
			if(!is_dir($UploadDirectory))
			{
			    mkdir($UploadDirectory,0777);
			}
			if(move_uploaded_file($_FILES['file']['tmp_name'], $UploadDirectory.$NewFileName))
			{
				// do other stuff
				$this->status['code']="200";
				$this->status['name']=$NewFileName;
                $this->status['path']=$UploadDirectory;
			}
			else
			{
				$this->status['code']="417";
				$this->status['err_path']=$UploadDirectory.$NewFileName;
				$this->status['err_msg']='Can not open directory!';
			}
			
		}
		else
		{
			$this->status['code']="404";
		}
		
        return $this->status;
	}
}
?>