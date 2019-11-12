<?php
class pihome extends construct
{
	public $ServerStatus;
	public $ExtHDD;
	public $ExtHDDcontent;
	public $ExtHDDtemp;
	public $HDDerr;
	public $Condensation;
	public $CondensationSTS;
	public $CondensationLVL;
	public $CPUtemperature;
	public $playerCPUtemperature;
	public $pcstatus;
	public $CondensationTemp;
	
	private $pcstatusjson;
	private $tempcorrection = 8.4;
	private $err;
	
	final protected function mainLogic()
	{
		$cfg = $this->readVSC('pihome');
		
		$this->config_mod($cfg[0],$cfg[1],$cfg[2]);
		if($_SESSION['perm']>=1100)
		{
			$this->getExtHDDstatus();
			$this->getCondensation();
			$this->getTemperatures();
			for ($i=0; $i < 10; $i++) { 
				$this->getServerIP($i);
			}
		}
	}

	/**
	 * Check sensor - IP save on server call
	 * 
	 * @return array
	 */
	final private function getServerIP($serverId)
	{
		$this->mysqlinterface->select(array(
			'sensors'=>array(
				'condition'=>array(
					'`sensor`="server-mastery-'.$serverId.'"'
				),
				'sort'=>array(
					'desc'=>'`id`'
				),
				'limit'=>1
			)
		));
		$data = $this->mysqlinterface->execute();
		if(stripos($data[0]['data'],'}')>0)
		{
			$this->ServerStatus[$serverId] = json_decode($data[0]['data'],true);
			$this->ServerStatus[$serverId]['date'] = $data[0]['date'];
			$this->ServerStatus[$serverId]['time'] = $data[0]['time'];
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Check sensor - getExtHDD status
	 * @return array
	 */
	final private function getExtHDDstatus()
	{
		$tempcorrection=$this->tempcorrection;
		$ExtHDD= file_get_contents('./switchexthdd', true);
		$ExtHDD = json_decode($ExtHDD,true);
		$this->ExtHDD = $ExtHDD;
		$this->ExtHDDcontent = fopen("http://".$this->ExtHDD['ip'], "r");
		$this->ExtHDDcontent = intval(stream_get_contents($this->ExtHDDcontent));
		$this->ExtHDDtemp = fopen("http://".$this->ExtHDD['ip']."/temperature", "r");
		$this->ExtHDDtemp = round((floatval(stream_get_contents($this->ExtHDDtemp))-floatval($tempcorrection)),1,PHP_ROUND_HALF_UP);
		$this->HDDerr = 0;
		return $this->ExtHDD;
	}
	
	/**
	 * getCondensation status
	 * @return array
	 */
	final private function getCondensation()
	{
		//condensation level parser
		$tempcorrection=$this->tempcorrection;//3.4||10 correction
		try
		{
			$this->mysqlinterface->select(array(
				'sensors'=>array(
					'condition'=>array(
						'`sensor`="waterCondensator"'
					),
					'sort'=>array(
						'desc'=>'`id`'
					),
					'limit'=>1
				)
			));
			
			$out = $this->mysqlinterface->execute();
			$this->Condensation = json_decode($out[0]['data'],true);
			$this->CondensationSTS = $this->Condensation['msg'];
			$this->CondensationLVL = $this->Condensation['water'];
			$this->CondensationTemp = round(($this->Condensation['temperature'] - $tempcorrection),1,PHP_ROUND_HALF_UP);
		}
		catch(Exception $e)
		{
			$this->err=$e->getMessage();
			$this->CondensationSTS = 'ERROR';
			$this->CondensationTemp = 'ERROR';
		}
		
		return $this->Condensation;
	}
	
	/**
	 * getTemperatures - get temperatures from pihome player
	 */
	final private function getTemperatures()
	{
		//CPU temperature
		$this->mysqlinterface->select(array(
			'sensors'=>array(
				'condition'=>array(
					'`sensor`="piplayer"'
				),
				'sort'=>array(
					'desc'=>'`id`'
				),
				'limit'=>1
			)
		));
		
		$out = $this->mysqlinterface->execute();
		$this->playerCPUtemperature = $out[0]['data'];
		
		$this->CPUtemperature = file_get_contents('./temperature', true);
		$this->CPUtemperature = explode('=',$this->CPUtemperature);
		$this->CPUtemperature = substr($this->CPUtemperature[1], 0, -3);
		
		try
		{
			$this->playerCPUtemperature=json_decode($this->playerCPUtemperature,true);
			$date=explode('-',$this->playerCPUtemperature['date']);
			if(($date[0].$date[1].$date[2])<date('Ymd'))
			{
				$this->playerCPUtemperature=array(
					"status"=>"not found",
					"code"=>404,
					"temp"=>"OFF"
				);
			}
		}
		catch(Exception $e)
		{
			$e->getMessage();
		}
		
		try
		{
			$pcjson = file_get_contents('./maincomputer', true);
			$this->pcstatusjson=json_decode($pcjson,true);
			if($this->pcstatusjson['status']==200)
			{
				$this->pcstatus=true;
			}
			else
			{
				$this->pcstatus=false;
			}
		}
		catch(Exception $e)
		{
			$e->getMessage();
			$this->pcstatus=false;
		}
	}
	
	/**
	 * Ajax
	 */
	final public function ajax()
	{
		require_once('./fw_modules/pihome/ajax_pihome.php');
		return $status;
	}
	
	/**
	 * Generate sensors data list
	 * 
	 * @return array
	 */
	final private function sensorsDayList()
	{
		$this->mysqlinterface->select(array(
			'sensors'=>array(
				'condition'=>array(
					'`date`="'.date('Y-m-d').'"'
				),
				'sort'=>array(
					'desc'=>'`id`'
				)
			)
		));
		return $this->mysqlinterface->execute();
	}
	
	/**
	 * Generate sensor IFTTT weather
	 * 
	 * @return array
	 */
	final public function sensorIFTTTweather()
	{
		$this->mysqlinterface->select(array(
			'sensors'=>array(
				'condition'=>array(
					'(`sensor`="iftttweather")'
				),
				'sort'=>array(
					'desc'=>'`id`'
				),
				'limit'=>1
			)
		));
		$data = $this->mysqlinterface->execute();
		$data = json_decode($data[0]['data'],true);
		return $data;
	}
	
	/**
	 * Generate users list table
	 * 
	 * structure:
	 * 		tr
	 * 			td = id
	 * 			td = sensor
	 * 			td = data
	 * 			td = date
	 * 			td = time
	 * 
	 * @return string
	 */
	final public function sensorsLines()
	{
		$full = $this->sensorsDayList();
		foreach($full as $v)
		{
			$data = json_decode($v['data'],true);
			$temp = ((!isset($data['hitemp']))?$data['temp']:$data['lotemp'].'-'.$data['hitemp'].'°C');
			$status = ($data['status']!=''?$data['status']:$data['tc']);
			$table .= "<tr class='even pointer'>
                        <td class=''>{$v['id']}</td>
                        <td class=''>{$v['sensor']}</td>
                        <td class=''>{$temp} {$status} {$data['msg']} {$data['temperature']} {$data['rawtemperature']}</td>
                        <td class=''>{$v['time']}</td>
                      </tr>".PHP_EOL;
		}
		return $table;
	}
	/*IFTTT report: {"atype":"iftttweather","hitemp":"33","lotemp":"19","tc":"Sunny","url":"https://ifttt.com/images/weather/sunny.png"}*/
	
	/**
	 * Generate robot event list
	 */
	final public function robotEventList()
	{
		$html = file_get_contents('http://'.$_GET['pirobot_ip']);
		
		//Create a new DOM document
		$dom = new DOMDocument;
		
		//Parse the HTML. The @ is used to suppress any parsing errors
		//that will be thrown if the $html string isn't valid XHTML.
		@$dom->loadHTML($html);

		//Get all links. You could also use any other tag name here,
		//like 'img' or 'table', to extract other tags.
		$links = $dom->getElementsByTagName('a');

		//Iterate over the extracted links and display their URLs
		foreach ($links as $link){
			//Extract and show the "href" attribute.
			//echo $link->nodeValue;
			$linkHref[] = $link->getAttribute('href');
		}
		
		return $linkHref;
	}
}
?>
