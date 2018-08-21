<?php
class pihome extends construct
{
	public $ExtHDD;
	public $HDDerr;
	public $Condensation;
	public $CondensationSTS;
	public $CondensationLVL;
	private $err;
	public $CPUtemperature;
	public $playerCPUtemperature;
	public $pcstatus;
	private $pcstatusjson;
	
	final protected function mainLogic()
	{
		$cfg = $this->readVSC('pihome');
		
		$this->config_mod($cfg[0],$cfg[1],$cfg[2]);
		if($_SESSION['perm']>=1100)
		{
			$this->getExtHDDstatus();
			$this->getCondensation();
			$this->getTemperatures();
			
			/*$this->linkmngr->addUrl('','dashboard_pi_page.html.php');
			$this->addMenuItem('Dashboard','','tachometer',1100);*/
		}
		/*if($_SESSION['perm']>=1110)
		{
			$this->linkmngr->addUrl('sensors','sensors_page.html.php');
			$this->addMenuItem('Sensors','sensors','gears',1110);
		}
		if($_SESSION['perm']>=1111)
		{
			$this->linkmngr->addUrl('exthdd','exthdd_pi_page.html.php');
			$this->addMenuItem('Ext HDD','exthdd','cloud',1111);
			
			$this->linkmngr->addUrl('gpio','gpio_pi_page.html.php');
			
			$this->linkmngr->addUrl('shutdown','_');
		}*/
		//$this->render();
	}
	
	/**
	 * Check sensor - getExtHDD status
	 * @return array
	 */
	final private function getExtHDDstatus()
	{
		//ExtHDD status parser
		/*if($this->is_json($this->ExtHDD))
		{*/
		$this->ExtHDD = file_get_contents('./switchexthdd', true);
		$this->ExtHDD = json_decode($this->ExtHDD,true);
		$this->ExtHDDcontent = fopen ("http://".$this->ExtHDD['ip'], "r");
		if (!$this->ExtHDDcontent) {
			exit;
		}
		$this->ExtHDDcontent = stream_get_contents($this->ExtHDDcontent);
		$this->HDDerr = 0;
		/*}
		else
		{
			$this->ExtHDDcontent = 1;
			$this->HDDerr = 1;
		}*/
		return $this->ExtHDD;
	}
	
	/**
	 * getCondensation status
	 * @return array
	 */
	final private function getCondensation()
	{
		//condensation level parser
		try
		{
			$this->Condensation = file_get_contents('./watercondensator', true);
			$this->Condensation = json_decode($this->Condensation,true);
			$this->CondensationSTS = $this->Condensation['msg'];
			$this->CondensationLVL = $this->Condensation['water'];
		}
		catch(Exception $e)
		{
			$this->err=$e->getMessage();
			$this->CondensationSTS = 'ERROR';
		}
		
		return $this->Condensation;
	}
	
	/**
	 * getTemperatures - get temperatures from pihome player
	 */
	final private function getTemperatures()
	{
		//CPU temperature
		$this->playerCPUtemperature = file_get_contents('./piplayertemperature', true);
		
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
                        <td class=''>{$temp} {$status} {$data['msg']}</td>
                        <td class=''>{$v['time']}</td>
                      </tr>".PHP_EOL;
		}
		return $table;
	}
	/*IFTTT report: {"atype":"iftttweather","hitemp":"33","lotemp":"19","tc":"Sunny","url":"https://ifttt.com/images/weather/sunny.png"}*/
}
?>
