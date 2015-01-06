<?php 
/*
* Modul: Content Reader
* Verzia: 13.001
* Funkcie: »ÌtaË obsahu
* ModID: CM001-13001
*/
function db_in($subfrm)
{
	$subfrm=str_replace('{db:select.all}','SELECT * ',$subfrm);
	
	$subfrm=str_replace('{db:from.all}','FROM ',$subfrm);
	$subfrm=str_replace('{db:register.mngr}','mod_mngr_register',$subfrm);
	
	$subfrm=str_replace('ERT','',$subfrm);
	$subfrm=str_replace('ATE','',$subfrm);
	$subfrm=str_replace('ETE','',$subfrm);
	$subfrm=str_replace('ROP','',$subfrm);
	$subfrm=str_replace('ert','',$subfrm);
	$subfrm=str_replace('ate','',$subfrm);
	$subfrm=str_replace('ete','',$subfrm);
	$subfrm=str_replace('rop','',$subfrm);
	
	return $subfrm;
}
function v_db($subfrm)
{
	$subfrm=md5("$subfrm");
	$submd5=md5("SELECT * FROM mod_mngr_register");
	if($subfrm==$submd5)
	{
		return true;
	}
	else
	{
		return false;
	}
}
if($perm>=1000)
{
	//opr·vnenie uûÌvateæa
	//nacitanie premmenn˝ch do formul·ru
	//zaciatok cyklu zobrazenia cisel vsetkych stranok
	$readsql="{db:select.all}{db:from.all}{db:register.mngr}";
	$readsql=db_in($readsql);
	if((v_db($readsql))==false)
	{
		unset($readsql);
	}
	$con = mysql_connect($DBHost, $DBUser, $DBPass);
	if(! $con)
	{
		die("<b>NemÙûem sa pripojiù:</b> ".mysql_error());
	}  
	mysql_select_db($DBName, $con);
	
	$result = mysql_query($readsql);
	
	while($row = mysql_fetch_array($result))
	{
	  $ContentCMD = $row['main_function'];
	  if($ContentCMD == "content(page_start)" && $_GET["pageid"]==$row['main_content'])
	  {
		$ContentEID = $row['end_slot'];
		$mod_mngr_use_it = 1;
	  }
	  if($mod_mngr_use_it == 1)
	  {
		if(($ContentCMD == "content(pname)") && ($ContentEID == $row['start_slot']))
		{
			$PageName = $row['main_content'];
			$ContentEID = $row['end_slot'];
			$ContentSID = $row['start_slot'];
		}
		if(($ContentCMD == "content(pkeywd)") && ($ContentEID == $row['start_slot']))
		{
			//$ContentData .= "<tr><td>Kæ˙ËovÈ slov·:</td><td><input type=\"text\" id=\"keywd\" name=\"keywd\" value=\"".$row['main_content']."\" /></td></tr>";
			$ContentEID = $row['end_slot'];
			$ContentSID = $row['start_slot'];
		}
		if(($ContentCMD == "content(pminytext)") && ($ContentEID == $row['start_slot']))
		{
			//$ContentData .= "<tr><td>Skr·ten· verzia:</td><td><textarea id=\"minytext\" name=\"minytext\" rows=\"5\" cols=\"40\">".$row['main_content']."</textarea></td></tr>";
			//$Content_mngr_Data .= pack("H*",$row['main_content']);
			$ContentEID = $row['end_slot'];
			$ContentSID = $row['start_slot'];
		}
		if(($ContentCMD == "content(pmaxitext)") && ($ContentEID == $row['start_slot']))
		{
			$Content_mngr_Data .= pack("H*",$row['main_content']);
			$ContentEID = $row['end_slot'];
			$ContentSID = $row['start_slot'];
		}
		if(($ContentCMD == "content(url)") && ($ContentEID == $row['start_slot']))
		{
			//$ContentData .= "<tr><td>URL:</td><td><input type=\"text\" id=\"url\" name=\"url\" value=\"".$row['main_content']."\" /></td></tr>";
			//$ContentData .= "<td>".$row['main_content']."</td>";
			$ContentEID = $row['end_slot'];
			$ContentSID = $row['start_slot'];
		}
		if(($ContentCMD == "content(pautor)") && ($ContentEID == $row['start_slot']))
		{
			$Content_mngr_Data .= "<div class=\"author_class\">".$row['main_content']."</div>";
			$ContentEID = $row['end_slot'];
			$ContentSID = $row['start_slot'];
		}
		if(($ContentCMD == "content(perm1)") && ($ContentEID == $row['start_slot']))
		{
			$ContentPerm1 = $row['main_content'];//na str·nkach
			$ContentEID = $row['end_slot'];
			$ContentSID = $row['start_slot'];
		}
		if(($ContentCMD == "content(perm2)") && ($ContentEID == $row['start_slot']))
		{
			$ContentPerm2 = $row['main_content'];//pre skupinu
			$ContentEID = $row['end_slot'];
			$ContentSID = $row['start_slot'];
			if($perm>=$ContentPerm2)
			{
				$ContentData .= $Content_mngr_Data;
			}
		}
		if(($ContentCMD == "content(perm3)") && ($ContentEID == $row['start_slot']))
		{
			$ContentPerm3 = $row['main_content'];//na URL
			$ContentEID = $row['end_slot'];
			$ContentSID = $row['start_slot'];
		}
		if(($ContentCMD == "content(cmd)") && ($ContentEID == $row['start_slot']))
		{
			$ContentTerminal .= $row['main_content'];//prÌkaz
			$ContentEID = $row['end_slot'];
			$ContentSID = $row['start_slot'];
		}
		if(($ContentCMD == "content(end)") && ($ContentEID == $row['start_slot']))
		{
			$ContentEID = $row['end_slot'];
			$ContentSID = $row['start_slot'];
			$mod_mngr_use_it = 0;
		}
	  }
    }
	mysql_close($con);
	$Content_mngr_Data="";
	//koniec cyklu
}
if($perm==0000)
{
	//opr·vnenie BAN
	$ContentData = "PrÌstup zablokovan˝!";
}
?>