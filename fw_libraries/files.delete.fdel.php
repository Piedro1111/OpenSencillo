<?php
/**
 * Delete files and folders from server
 * @name fdel
 * @version 2015.002
 * @category Sencillo Library
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class fdel
{
	protected $data;
	
	/**
	 * Construct
	 * @param string $storage
	 */
	public function __construct($storage)
	{
		$this->data['path']=$storage;
		$this->data['stored']=scandir($storage);
	} 
	
	/**
	 * Return debug information
	 * @return array
	 */
	public function debug()
	{
		return $this->data;
	}
	
	/**
	 * Delete one file
	 * @param string $name
	 * @return boolean
	 */
	public function deleteFile($name)
	{
		$dir = $this->data['path'];
		$status = false;
		$output = "<script type='text/javascript'>console.log( 'Delete: file $name not found' );</script>";
		if(file_exists($dir.$name))
		{
			if(unlink($dir.$name)&&($name!='.')&&($name!='..'))
			{
				$output = "<script type='text/javascript'>console.log( 'Delete: $name' );</script>";
				$status = true;
			}
			else 
			{
				$output = "<script type='text/javascript'>console.log( 'Delete: access denied for $name' );</script>";
				$status = false;
			}
		}
		$this->data['output'][]=$output;
		return $status;
	}
	
	/**
	 * Delete folder by name
	 * @param string $name
	 * @return boolean
	 */
	public function deleteFolder($name)
	{
		$dir = $this->data['path'];
		if(rmdir($dir.$name)&&($name!='.')&&($name!='..'))
		{
			$status = true;
			$output = "<script type='text/javascript'>console.log( 'Delete: $name' );</script>";
		}
		else 
		{
			$status = false;
			$output = "<script type='text/javascript'>console.log( 'Delete: access denied for $name' );</script>";
		}
		$this->data['output'][]=$output;
		return $status;
	}
	/**
	 * Delete old file by expire day
	 * @param number $olderThan
	 * @return number
	 */
	public function deleteOldFile($olderThan=7)
	{
		$dir = $this->data['path'];
		$filename = $this->data['stored'];
		$max = $olderThan;//day to delete
		$i=0;
		foreach($filename as $val)
		{
			if((file_exists($dir.$val))&&($val!='.')&&($val!='..'))
			{
				$time = date("Ymd", filemtime($dir.$val))+$max;
				if($time<date("Ymd"))
				{
					if(unlink($dir.$val))
					{
						$output .= "<script type='text/javascript'>console.log( 'Delete: $val' );</script>";
						$i++;
					}
					else 
					{
						$output .= "<script type='text/javascript'>console.log( 'Delete: access denied for $val' );</script>";
					}
				}
			}
		}
		$output .= "<script type='text/javascript'>console.log( 'Out of date: $i files' );</script>";
		$this->data['output']=$output;
		return $i;
	}
}
?>