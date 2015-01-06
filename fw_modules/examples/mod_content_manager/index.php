<?php
include_once("data/mcm_cfg.php");
if(isset($_GET['mod_content_mngr']))
{
	$ContentData .= "<table>";
	if($mcm_npg==1)
	{
		$ContentData .= "<tr><td><a href=\"admin.php?pageid=index7&amp;mod_content_mngr=create_page\" target=\"_self\">New page</a></td><td>Insert new page to system database.</td></tr>";
	}
	if($mcm_upg==1)
	{
		$ContentData .= "<tr><td><a href=\"admin.php?pageid=index7&amp;mod_content_mngr=edit_page\" target=\"_self\">Edit page</a></td><td>Edit old page in system database.</td></tr>";
	}
	if($mcm_nub==1)
	{
		//$ContentData .= "<tr><td><a href=\"admin.php?pageid=index7&amp;mod_content_mngr=create_menu\" target=\"_self\">Nové menu</a></td><td>Umožňuje pridať nové menu do databázy a priradiť odkazom obsah.</td></tr>";
		//$ContentData .= "<tr><td><a href=\"admin.php?pageid=index7&amp;mod_content_mngr=edit_menu\" target=\"_self\">Upraviť existujúce menu</a></td><td>Umožňuje upraviť existujúce menu a zrušiť jeho zmazanie.</td></tr>";
	}
	if($mcm_udd==1)
	{
		//$ContentData .= "<tr><td><a href=\"admin.php?pageid=index7&amp;mod_content_mngr=file_system\" target=\"_self\">File system</a></td><td>File manipulation system.</td></tr>";
		//$ContentData .= "<tr><td><a href=\"admin.php?pageid=index7&amp;mod_content_mngr=mngr_options\" target=\"_self\">Module options</a></td><td>Umožňuje nastaviť automatického administrátora, zálohovanie a správanie sa modulu.</td></tr>";
		$ContentData .= "<tr><td><a href=\"admin.php?pageid=index7&amp;mod_content_mngr=delete\" target=\"_self\">Remove content</a></td><td>Remove pages from system.</td></tr>";
		include_once("data/mcm_data.php");
	}
	$ContentData .= "</table>";
	if($_GET["mod_content_mngr"]=="create_page")
	{
		if($_GET["pageid"]=="index7")
		{
			$PageName = "New page";
			$ContentData = "$tMCE<hr /><b>New page</b><br />";
			$ContentData .= '<form action="admin.php?pageid=index7&amp;mod_content_mngr=send" method="post">';
			$ContentData .= "<table>";
			$ContentData .= "<tr><td>Unique page identification:</td><td><input type=\"text\" id=\"pageid\" name=\"pageid\"/></td></tr>";
			$ContentData .= "<tr><td>Page title:</td><td><input type=\"text\" id=\"pagename\" name=\"pagename\"/></td></tr>";
			if($mcm_cfg[0]==1){$ContentData .= "<tr><td>Keywords:</td><td><input type=\"text\" id=\"keywd\" name=\"keywd\"/></td></tr>";}
			if($mcm_cfg[1]==1){$ContentData .= "<tr><td>Description:</td><td><textarea id=\"minytext\" name=\"minytext\" rows=\"5\" cols=\"40\"></textarea></td></tr>";}
			if($mcm_cfg[2]==1){$ContentData .= "<tr><td>Content:</td><td><textarea id=\"maxitext\" name=\"maxitext\" rows=\"10\" cols=\"40\"></textarea></td></tr>";}
			if($mcm_cfg[3]==1){$ContentData .= "<tr><td>URL:</td><td><input type=\"text\" id=\"url\" name=\"url\"/></td></tr>";}
			if($mcm_cfg[4]==1){$ContentData .= "<tr><td>Author:</td><td><input type=\"text\" id=\"autor\" name=\"autor\"/></td></tr>";}
			if($mcm_cfg[5]==1){$ContentData .= "<tr><td>View on page:</td><td><input type=\"text\" id=\"perm1\" name=\"perm1\"/><small>(pageid:index1,index2,index3)</small></td></tr>";}
			if($mcm_cfg[6]==1){$ContentData .= "<tr><td>View for grup:</td><td><input type=\"text\" id=\"perm2\" name=\"perm2\"/><small>(perm:1000)</small></td></tr>";}
			if($mcm_cfg[7]==1){$ContentData .= "<tr><td>View on URL:</td><td><input type=\"text\" id=\"perm3\" name=\"perm3\"/><small>(url:index.php?pageid=index1)</small></td></tr>";}
			if($mcm_cfg[8]==1){$ContentData .= "<tr><td>Command:</td><td><input type=\"text\" id=\"cmd\" name=\"cmd\"/></td></tr>";}
			$ContentData .= "<tr><td><input type=\"submit\" value=\"Submit\" /></td><td></td></tr>";
			$ContentData .= "</table>";
			$ContentData .= "</form>";
		}
	}
	if($_GET["mod_content_mngr"]=="edit_page")
	{
		if($_GET["pageid"]=="index7")
		{
			$PageName = "Edit page (step 1 z 2)";
			$ContentData = "<hr /><b>Edit page</b><br />";
			$ContentData .= '<form action="admin.php?pageid=index7b&amp;mod_content_mngr=edit_page" method="post">';
			$ContentData .= "<table>";
			$ContentData .= "<tr><td>Open ID:</td><td><input type=\"text\" id=\"pageid\" name=\"pageid\"/></td></tr>";
			$ContentData .= "<tr><td><input type=\"submit\" value=\"Submit\" /></td><td></td></tr>";
			$ContentData .= "</table>";
			$ContentData .= "</form>";
			$ContentData .= "<table>";
			$ContentData .= "<tr><td>ID</td><td>Page title</td><td>URL</td></tr>";
			//zaciatok cyklu zobrazenia cisel vsetkych stranok
			$readsql="SELECT * FROM mod_mngr_register";
			$con = mysql_connect($DBHost, $DBUser, $DBPass);
			if(! $con)
			{
			  die("<b>Connection problem:</b> ".mysql_error());
			}
			mysql_select_db($DBName, $con);

			$result = mysql_query($readsql);

			$max_load_online=0;
			while($row = mysql_fetch_array($result))
			{
			  $ContentCMD = $row['main_function'];
			  if($ContentCMD == "content(page_start)")
			  {
			    $ContentData .= "<tr>";
				$ContentData .= "<td>".$row['main_content']."</td>";
				$ContentEID = $row['end_slot'];
			  }
			  if(($ContentCMD == "content(pname)") && ($ContentEID == $row['start_slot']))
			  {

				$ContentData .= "<td>".$row['main_content']."</td>";
				$ContentEID = $row['end_slot'];
			  }
			  if(($ContentCMD == "content(url)") && ($ContentEID == $row['start_slot']))
			  {
				$ContentData .= "<td>".$row['main_content']."</td>";
				$ContentEID = $row['end_slot'];
				$ContentSID = $row['start_slot'];
			  }
			  if(($ContentCMD == "content(end)") && ($ContentEID == $row['start_slot']))
			  {
			    $ContentData .= "</tr>";
				$ContentEID = $row['end_slot'];
				$ContentSID = $row['start_slot'];
			  }
			}
			mysql_close($con);
			//koniec cyklu
			$ContentData .= "</table>";
		}
		if($_GET["pageid"]=="index7b")
		{
			//nacitanie premmenných do formuláru
			//zaciatok cyklu zobrazenia cisel vsetkych stranok
			$readsql="SELECT * FROM mod_mngr_register";
			$con = mysql_connect($DBHost, $DBUser, $DBPass);
			if(! $con)
			{
			  die("<b>Nemôžem sa pripojiť:</b> ".mysql_error());
			}
			mysql_select_db($DBName, $con);

			$result = mysql_query($readsql);

			$max_load_online=0;

			$PageName = "Edit page (step 2 z 2)";
			$ContentData = "$tMCE<hr /><b>Edit page</b><br />";
			$ContentData .= '<form action="admin.php?pageid=index7&amp;mod_content_mngr=update" method="post">';
			$ContentData .= "<table>";

			while($row = mysql_fetch_array($result))
			{
			  $ContentCMD = $row['main_function'];
			  if($ContentCMD == "content(page_start)" && $_POST["pageid"]==$row['main_content'])
			  {
				$ContentEID = $row['end_slot'];
				$mod_mngr_use_it = 1;
				$ContentData .= '<input type="hidden" name="pageid" value="'.$row['main_content'].'" />';
			  }
			  if($mod_mngr_use_it == 1)
			  {

				  if(($ContentCMD == "content(pname)") && ($ContentEID == $row['start_slot']))
				  {
					$ContentData .= "<tr><td>Page title:</td><td><input type=\"text\" id=\"pagename\" name=\"pagename\" value=\"".$row['main_content']."\" /></td></tr>";
					$ContentEID = $row['end_slot'];
					$ContentSID = $row['start_slot'];
				  }
				  if(($ContentCMD == "content(pkeywd)") && ($ContentEID == $row['start_slot']))
				  {
					if($mcm_cfg[0]==1){$ContentData .= "<tr><td>Keywords:</td><td><input type=\"text\" id=\"keywd\" name=\"keywd\" value=\"".$row['main_content']."\" /></td></tr>";}
					$ContentEID = $row['end_slot'];
					$ContentSID = $row['start_slot'];
				  }
				  if(($ContentCMD == "content(pminytext)") && ($ContentEID == $row['start_slot']))
				  {
					if($mcm_cfg[1]==1){$ContentData .= "<tr><td>Description:</td><td><textarea id=\"minytext\" name=\"minytext\" rows=\"5\" cols=\"40\">".pack("H*",$row['main_content'])."</textarea></td></tr>";}
					$ContentEID = $row['end_slot'];
					$ContentSID = $row['start_slot'];
				  }
				  if(($ContentCMD == "content(pmaxitext)") && ($ContentEID == $row['start_slot']))
				  {
					if($mcm_cfg[2]==1){$ContentData .= "<tr><td>Content:</td><td><textarea id=\"maxitext\" name=\"maxitext\" rows=\"10\" cols=\"40\">".pack("H*",$row['main_content'])."</textarea></td></tr>";}
					$ContentEID = $row['end_slot'];
					$ContentSID = $row['start_slot'];
				  }
				  if(($ContentCMD == "content(url)") && ($ContentEID == $row['start_slot']))
				  {
					if($mcm_cfg[3]==1){$ContentData .= "<tr><td>URL:</td><td><input type=\"text\" id=\"url\" name=\"url\" value=\"".$row['main_content']."\" /></td></tr>";}
					$ContentEID = $row['end_slot'];
					$ContentSID = $row['start_slot'];
				  }
				  if(($ContentCMD == "content(pautor)") && ($ContentEID == $row['start_slot']))
				  {
					if($mcm_cfg[4]==1){$ContentData .= "<tr><td>Author:</td><td><input type=\"text\" id=\"autor\" name=\"autor\" value=\"".$row['main_content']."\" /></td></tr>";}
					$ContentEID = $row['end_slot'];
					$ContentSID = $row['start_slot'];
				  }
				  if(($ContentCMD == "content(perm1)") && ($ContentEID == $row['start_slot']))
				  {
					if($mcm_cfg[5]==1){$ContentData .= "<tr><td>View on pages:</td><td><input type=\"text\" id=\"perm1\" name=\"perm1\" value=\"".$row['main_content']."\" /></td></tr>";}
					$ContentEID = $row['end_slot'];
					$ContentSID = $row['start_slot'];
				  }
				  if(($ContentCMD == "content(perm2)") && ($ContentEID == $row['start_slot']))
				  {
					if($mcm_cfg[6]==1){$ContentData .= "<tr><td>View for group:</td><td><input type=\"text\" id=\"perm2\" name=\"perm2\" value=\"".$row['main_content']."\" /></td></tr>";}
					$ContentEID = $row['end_slot'];
					$ContentSID = $row['start_slot'];
				  }
				  if(($ContentCMD == "content(perm3)") && ($ContentEID == $row['start_slot']))
				  {
					if($mcm_cfg[7]==1){$ContentData .= "<tr><td>View on URL:</td><td><input type=\"text\" id=\"perm3\" name=\"perm3\" value=\"".$row['main_content']."\" /></td></tr>";}
					$ContentEID = $row['end_slot'];
					$ContentSID = $row['start_slot'];
				  }
				  if(($ContentCMD == "content(cmd)") && ($ContentEID == $row['start_slot']))
				  {
					if($mcm_cfg[8]==1){$ContentData .= "<tr><td>Command:</td><td><input type=\"text\" id=\"cmd\" name=\"cmd\" value=\"".$row['main_content']."\" /></td></tr>";}
					$ContentEID = $row['end_slot'];
					$ContentSID = $row['start_slot'];
				  }
				  if(($ContentCMD == "content(end)") && ($ContentEID == $row['start_slot']))
				  {
					$ContentEID = $row['end_slot'];
					$ContentSID = $row['start_slot'];
					$mod_mngr_use_it = 0;
					$ContentData .= "<tr><td><input type=\"submit\" value=\"Edit\" /></td><td></td></tr>";
				  }
			   }
			}
			mysql_close($con);
			//koniec cyklu
			$ContentData .= "</table>";
			$ContentData .= "</form>";
		}
	}
	if($_GET["mod_content_mngr"]=="mngr_options")
	{
		$delete=Array("id"=>array());
		$mod_counter=0;
		if($_GET["pageid"]=="index7")
		{
			$PageName = "Content options";
			$ContentData = "<hr /><b>Content options</b><br />";
			$ContentData .= '<form action="admin.php?pageid=index7&amp;mod_content_mngr=mngr_options" method="post">';
			$ContentData .= "<input type=\"hidden\" id=\"security\" name=\"security\" value=\"alldelete\" />";
			$ContentData .= "<table>";
			$readsql="SELECT * FROM mod_mngr_register";
			$con = mysql_connect($DBHost, $DBUser, $DBPass);
			if(! $con)
			{
			  die("<b>Connection problem:</b> ".mysql_error());
			}
			mysql_select_db($DBName, $con);

			$result = mysql_query($readsql);
			$ContentData .= "<tr><td>Delete squention</td><td>Row for removed (ID)</td></tr>";
			while($row = mysql_fetch_array($result))
			{
			  $ContentCMD = $row['main_function'];
			  if(($ContentCMD == "content(delete_sequention)") || ($ContentCMD == "content(delete_sequence)") || ($ContentCMD == "delete(sequention)"))
			  {
			    $ContentData .= "<tr>";
				$ContentData .= "<td>".$row['main_content']."</td><td>".$row['id'].";";
				$ContentEID = $row['end_slot'];
				$delete["id"][$mod_counter] = $row['id'];
				$mod_counter++;
			  }
			  if($ContentEID == $row['start_slot'])
			  {
				$ContentEID = $row['end_slot'];
				$delete["id"][$mod_counter] = $row['id'];
				$ContentData .= $row['id'].";";
				$mod_counter++;
			  }
			  if(($ContentCMD == "content(end)") && ($ContentEID == $row['start_slot']))
			  {
				$delete["id"][$mod_counter] = $row['id'];
				$ContentData .= $row['id'].";";
				$mod_counter++;
				$ContentData .= "</td>";
				$ContentData .= "</tr>";
				$ContentEID = $row['end_slot'];
				$ContentSID = $row['start_slot'];
			  }
			}
			mysql_close($con);
			$ContentData .= "</table>";
			$ContentData .= "<input type=\"submit\" value=\"Remove\" />";
			$ContentData .= "</form>";
		}

	}
	if($_POST["security"]=="alldelete")
	{
		for($mod_counter_i=0;$mod_counter_i<=$mod_counter;$mod_counter_i++)
		{
			$front_e = $delete["id"][$mod_counter_i];
			$sql="DELETE FROM mod_mngr_register WHERE id='$front_e'";
			sql_freecode_ng($DBUser,$DBName,$DBPass,$DBHost,$sql);
			$sql="ALTER TABLE `mod_mngr_register` ORDER BY `id`";
			sql_freecode_ng($DBUser,$DBName,$DBPass,$DBHost,$sql);
		}
	}
	if($_GET["mod_content_mngr"]=="delete")
	{
		if($_GET["pageid"]=="index7")
		{
			$PageName = "Remove content";
			$ContentData = "<hr /><b>Remove content</b><br />";
			$ContentData .= '<form action="admin.php?pageid=index7b&amp;mod_content_mngr=delete" method="post">';
			$ContentData .= "<table>";
			$ContentData .= "<tr><td>Identification:</td><td><input type=\"text\" id=\"mainid\" name=\"mainid\"/></td></tr>";
			$ContentData .= "<tr><td><input type=\"submit\" value=\"Add to remove\" /></td><td></td></tr>";
			$ContentData .= "</table>";
			$ContentData .= "</form>";
			$ContentData .= "<table>";
			//zaciatok cyklu zobrazenia cisel vsetkych stranok
			$ContentData .= "<tr><td>ID</td><td>Page title</td><td>URL</td></tr>";
			//zaciatok cyklu zobrazenia cisel vsetkych stranok
			$readsql="SELECT * FROM mod_mngr_register";
			$con = mysql_connect($DBHost, $DBUser, $DBPass);
			if(! $con)
			{
			  die("<b>Nemôžem sa pripojiť:</b> ".mysql_error());
			}
			mysql_select_db($DBName, $con);

			$result = mysql_query($readsql);
			$max_load_online=0;
			while($row = mysql_fetch_array($result))
			{
			  $ContentCMD = $row['main_function'];
			  if($ContentCMD == "content(page_start)")
			  {
			    $ContentData .= "<tr>";
				$ContentData .= "<td>".$row['main_content']."</td>";
				$ContentEID = $row['end_slot'];
			  }
			  if(($ContentCMD == "content(pname)") && ($ContentEID == $row['start_slot']))
			  {

				$ContentData .= "<td>".$row['main_content']."</td>";
				$ContentEID = $row['end_slot'];
			  }
			  if(($ContentCMD == "content(url)") && ($ContentEID == $row['start_slot']))
			  {
				$ContentData .= "<td>".$row['main_content']."</td>";
				$ContentEID = $row['end_slot'];
				$ContentSID = $row['start_slot'];
			  }
			  if(($ContentCMD == "content(end)") && ($ContentEID == $row['start_slot']))
			  {
			    $ContentData .= "</tr>";
				$ContentEID = $row['end_slot'];
				$ContentSID = $row['start_slot'];
			  }
			}
			mysql_close($con);
			$ContentData .= "</table>";
			//koniec cyklu
		}
		if($_GET["pageid"]=="index7b")
		{
			$delete_sequence=$_POST["mainid"];
			$PageName = "Remove page";
			$ContentData = "<hr /><b>Remove page</b><br />";
			$ContentData .= "<table>";
			$ContentData .= "<tr><td>ID:</td><td>".$delete_sequence."</td></tr>";
			$ContentData .= "<tr><td>Status:</td><td>Ready to remove.</td></tr>";;
			$ContentData .= "</table>";
			$sql="UPDATE mod_mngr_register SET main_function = 'content(delete_sequence)' WHERE main_content = '$delete_sequence' AND main_function = 'content(page_start)'";
			sql_freecode_ng($DBUser,$DBName,$DBPass,$DBHost,$sql);
			$sql="UPDATE mod_mngr_register SET main_function = 'content(delete_sequence)' WHERE main_content = '$delete_sequence' AND main_function = 'content(menu_start)'";
			sql_freecode_ng($DBUser,$DBName,$DBPass,$DBHost,$sql);
		}

	}
	if($_GET["mod_content_mngr"]=="update")
	{
		if($_POST["pageid"]!="")
		{
			for($mngr_counter_i=0;$mngr_counter_i<=11;$mngr_counter_i++)
			{
				switch($mngr_counter_i)
				{
					case 0:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["pageid"];
						$main_function="content(page_start)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 1:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["pagename"];
						$main_function="content(pname)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 2:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["keywd"];
						$main_function="content(pkeywd)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 3:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=bin2hex($_POST["minytext"]);
						$main_function="content(pminytext)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 4:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=bin2hex($_POST["maxitext"]);
						$main_function="content(pmaxitext)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 5:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["url"];
						$main_function="content(url)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 6:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["autor"];
						$main_function="content(pautor)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 7:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["perm1"];
						$main_function="content(perm1)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 8:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["perm2"];
						$main_function="content(perm2)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 9:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["perm3"];
						$main_function="content(perm3)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 10:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["cmd"];
						$main_function="content(cmd)";
						$end_slot=$_POST["pageid"]."_end";
					break;
					case 11:
						$start_slot=$_POST["pageid"]."_end";
						$main_content="content(separator)";
						$main_function="content(end)";
						$end_slot=$_POST["pageid"]."_end";
					break;
				}
				$sql="UPDATE mod_mngr_register SET main_content = '$main_content' WHERE start_slot = '$start_slot' AND main_function = '$main_function'";
				sql_freecode_ng($DBUser,$DBName,$DBPass,$DBHost,$sql);
				if($_GET["pageid"]=="index7")
				{
					//uloženie nových premmenných
					$PageName = "Page";
					$ContentData = "<hr /><b>Content saved.</b><br />";
				}
			}
		}
	}
	if($_GET["mod_content_mngr"]=="send")
	{
		if($_POST["pageid"]!="")
		{
			for($mngr_counter_i=0;$mngr_counter_i<=11;$mngr_counter_i++)
			{
				switch($mngr_counter_i)
				{
					case 0:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["pageid"];
						$main_function="content(page_start)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 1:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["pagename"];
						$main_function="content(pname)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 2:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["keywd"];
						$main_function="content(pkeywd)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 3:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=bin2hex($_POST["minytext"]);
						$main_function="content(pminytext)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 4:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=bin2hex($_POST["maxitext"]);
						$main_function="content(pmaxitext)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 5:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["url"];
						$main_function="content(url)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 6:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["autor"];
						$main_function="content(pautor)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 7:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["perm1"];
						$main_function="content(perm1)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 8:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["perm2"];
						$main_function="content(perm2)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 9:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["perm3"];
						$main_function="content(perm3)";
						$end_slot=$_POST["pageid"];
						$end_slot.=$mngr_counter_i+1;
					break;
					case 10:
						$start_slot=$_POST["pageid"];
						$start_slot.=$mngr_counter_i;
						$main_content=$_POST["cmd"];
						$main_function="content(cmd)";
						$end_slot=$_POST["pageid"]."_end";
					break;
					case 11:
						$start_slot=$_POST["pageid"]."_end";
						$main_content="content(separator)";
						$main_function="content(end)";
						$end_slot=$_POST["pageid"]."_end";
					break;
				}
				$sql="INSERT INTO mod_mngr_register (id, start_slot, main_content, main_function, end_slot) VALUES ('', '$start_slot', '$main_content', '$main_function', '$end_slot')";
				sql_freecode_ng($DBUser,$DBName,$DBPass,$DBHost,$sql);
				if($_GET["pageid"]=="index7")
				{
					//uloženie nových premmenných
					$PageName = "New page";
					$ContentData = "<hr /><b>Content saved.</b><br />";
				}
			}
		}
	}
	$sql="ALTER TABLE `mod_mngr_register` ORDER BY `id`";
	sql_freecode_ng($DBUser,$DBName,$DBPass,$DBHost,$sql);
}
else
{
	//oprávnenie užívateľa
	//nacitanie premmenných do formuláru
	//zaciatok cyklu zobrazenia cisel vsetkych stranok
	$readsql="SELECT * FROM mod_mngr_register";
	$con = mysql_connect($DBHost, $DBUser, $DBPass);
	if(! $con)
	{
		die("<b>Nemôžem sa pripojiť:</b> ".mysql_error());
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
			//$ContentData .= "<tr><td>Kľúčové slová:</td><td><input type=\"text\" id=\"keywd\" name=\"keywd\" value=\"".$row['main_content']."\" /></td></tr>";
			$ContentEID = $row['end_slot'];
			$ContentSID = $row['start_slot'];
		}
		if(($ContentCMD == "content(pminytext)") && ($ContentEID == $row['start_slot']))
		{
			//$ContentData .= "<tr><td>Skrátená verzia:</td><td><textarea id=\"minytext\" name=\"minytext\" rows=\"5\" cols=\"40\">".$row['main_content']."</textarea></td></tr>";
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
			$ContentPerm1 = $row['main_content'];//na stránkach
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
			$ContentTerminal .= $row['main_content'];//príkaz
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
	//koniec cyklu
}
$ModulStatus="OK";
?>
