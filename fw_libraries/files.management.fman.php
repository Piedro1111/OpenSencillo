<?php 
class filesList
{
    private $files;
    private $out;
    protected $path;
    protected $folderlist;
    
    public function __construct($path)
    {
        $this->files = scandir($path);
        $this->path = $path;
        
        if (!file_exists($path)) 
        {
            mkdir($path, 0777, true);
            chmod($path,0777);
        }
    }
    /**
     * Create complet file list
     * 
     * @param string $dir (path)
     * 
     * @return array
     */
    public function scanDir($dir='./')
    {
        $this->folderlist = array();
    	$this->findFiles($dir, $this->folderlist);
    	return $this->folderlist;
    }
    /**
     * Get all files, folders, subfiles and subfolders from directory
     * 
     *  @param string $dir (path to start directory)
     *  @param array  &$dir_array (array for update)
     */
    public function findFiles($dir, &$dir_array)
    {
        // Create array of current directory
        $files = scandir($dir);
       
        if(is_array($files))
        {
            foreach($files as $val)
            {
                // Skip home and previous listings
                if($val == '.' || $val == '..')
                    continue;
               
                // If directory then dive deeper, else add file to directory key
                if(is_dir($dir.'/'.$val))
                {
                    // Add value to current array, dir or file
                    $dir_array[$dir][] = $val;
                   
                    findFiles($dir.'/'.$val, $dir_array);
                }
                else
                {
                    $dir_array[$dir][] = $val;
                }
            }
        }
        ksort($dir_array);
    }
    
    /**
     * Scan directory structure and recursive scannig subdirectory structure
     * 
     * @param string $dir
     * 
     * @return array
     */
    public function findFilesStructure($dir)
    {
        $result = array();
        
        $cdir = scandir($dir);
        foreach ($cdir as $key => $value)
        {
            if (!in_array($value,array(".","..")))
            {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
                {
                    $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                }
                else
                {
                    $result[] = $value;
                }
            }
        }
        
        return $result;
    }
    /**
     * Convert array to html list
     * @param array $style
     * @param array $folderStyle
     * @param bool $dot
     * 
     * @return string
     */
    public function arrayToHtml($style,$folderStyle=null,$ftypes=null,$dot=null)
    {
        $i=0;
        $this->out='';
        
        while(sizeof($this->files)>$i)
        {
            if(($dot==false)||($dot==null))
            {
                if(stristr($this->files[$i],$ftypes)!=false)
                {
                    //is file
                    if(($this->files[$i]!='.')&&($this->files[$i]!='..'))
                    {
                        $this->out.=$style[0].$this->files[$i++].$style[1];
                    }
                    else
                    {
                        $i++;
                    }
                }
                else
                {
                    //is folder
                    if(($this->files[$i]!='.')&&($this->files[$i]!='..'))
                    {
                        $this->out.=$folderStyle[0].$this->files[$i++].$folderStyle[1];
                    }
                    else
                    {
                        $i++;
                    }
                }
            }
            else 
            {
                if(is_file($this->files[$i]))
                {
                    $this->out.=$style[0].$this->files[$i++].$style[1];
                }
                else 
                {
                    $this->out.=$folderStyle[0].$this->files[$i++].$folderStyle[1];
                }
            }
        }
        return $this->out;
    }
}
?>