<?php
/**
 * Core information
 * @name Sencillo Core - coreSencillo
 * @version 2017.104
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
		$version = '2017';
		$layout	 = '1';
		$build	 = '04';
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
		if($_GET['install']!='true')
		{
			$this->io_validator();
		}
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
 * @version 2017.104
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
?>
