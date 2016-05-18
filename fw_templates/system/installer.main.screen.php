<?php
/*--------------------------------------------------------------------------.
|  Software: Sencillo Default Theme                                         |
|   Version: 2015.109                                                       |
|   Contact: ph@mastery.sk                                                  |
| ------------------------------------------------------------------------- |
|    Author: Bc. Peter Horváth (original founder)                           |
| Copyright (c) 2015, Bc. Peter Horváth. All Rights Reserved.               |
| ------------------------------------------------------------------------- |
|   License: Distributed under the General Public License (GPL)             |
|            http://www.gnu.org/licenses/gpl-3.0.html                       |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'--------------------------------------------------------------------------*/

$endstatus=0;
$PHPversion=explode(".",phpversion());
foreach($ini['layout'] as $key=>$val)
{
	$style.="$key:$val;";
}
$action="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."?install={$ini['installer']['initialize']}";
if(($_GET['install']!=$ini['installer']['initialize'])||($_POST['user-new-pass']!=$_POST['user-rtp-pass'])||(empty($_POST['user-new-pass'])))
{
	if((floatval($PHPversion[0].'.'.$PHPversion[1]))>=floatval($ini['installer']['minimalphp']))
	{
		foreach($ini['perm_options_list'] as $key=>$val)
		{
			if($key==="default")
			{
				$default=$val;
			}
			else
			{
				$outpermlist .= "<option value='$key'".($default===$key?' selected':'').">$val</option>".PHP_EOL;
			}
		}
		foreach($ini['sql_options_list'] as $key=>$val)
		{
			if($key==="default")
			{
				$default=$val;
			}
			else
			{
				$outsqllist .= "<option value='$key'".($default===$key?' selected':'').">$val</option>".PHP_EOL;
			}
		}
		foreach($ini['cache_options_list'] as $key=>$val)
		{
			if($key==="default")
			{
				$default=$val;
			}
			else
			{
				$outcachelist .= "<option value='$key'".($default==$key?' selected':'').">$val</option>".PHP_EOL;
			}
		}
	}
	else
	{
		$endstatus=2;
	}
}
else
{
	if($ini['actions']['onsuccess']==="location")
	{
		header('Location: http://'.$_SERVER['SERVER_NAME']);
		$endstatus=1;
	}
}
if(($_POST['user-new-pass']!==$_POST['user-rtp-pass'])&&(!empty($_POST['user-new-pass']))&&(!empty($_POST['user-rtp-pass'])))
{
	$endstatus=3;
}
if(0777!==(fileperms('../fw_headers/') & 0777))
{
	$endstatus=4;
}
?>
	<body>
		<div class='container' style='<?=$style;?>'>
			<?if($endstatus===0):?>
			<form method='post' action='<?=$action;?>'>
				<table class='table table-striped'>
					<tr><td><span class='glyphicons glyphicons-circle-info'></span><kbd>About <?=$afterBootUp[0]->info['FWK'];?></kbd></td><td></td></tr>
					<tr><td><b>System:</b></td><td><?=$afterBootUp[0]->info['FWK'];?></td></tr>
					<tr><td><b>By:</b></td><td><?=$afterBootUp[0]->info['CPY'];?></td></tr>
					<tr><td><b>Homepage:</b></td><td><a href='http://www.opensencillo.com' target='_blank'>opensencillo.com</a></td></tr>
					<tr><td><b>PHP:</b></td><td><?=$PHPversion[0].".".$PHPversion[1];?></td></tr>
					<tr><td><b>DB charset:</b></td><td>UTF-8</td></tr>
					<tr><td><b>System charset:</b></td><td>UTF-8</td></tr>
					<tr><td><b>Installer status:</b></td><td><?=($ini['installer']['testcheck']=="true"?"OK":"Error");?></td></tr>
					
					<tr><td><span class='glyphicons glyphicons-old-man'></span><kbd>Superuser</kbd></td><td></td></tr>
					<tr><td><b>User:</b></td><td><input type='text' value='<?=$_POST['user-new-name'];?>' name='user-new-name' required></td></tr>
					<tr><td><b>Email:</b></td><td><input type='email' value='<?=$_POST['user-new-mail'];?>' name='user-new-mail' required></td></tr>
					<tr class='failgroupe1'><td><b>Pass:</b></td><td><input type='password' value='password1' name='user-new-pass' required></td></tr>
					<tr class='failgroupe1'><td><b>Retype pass:</b></td><td><input type='password' value='password2' name='user-rtp-pass' required></td></tr>
					<tr><td><b>Permission:</b></td><td>
						<select name='perm' <?=$ini['options']['perm'];?>>
							<?=$outpermlist;?>
						</select>
					</td></tr>
					<tr><td><span class='glyphicons glyphicons-database'></span><kbd>Database</kbd></td><td></td></tr>
					<tr><td><span class='halflings halflings-hdd'></span><b>Host:</b></td><td><input type='text' value='<?=$_POST['host'];?>' name='host' required></td></tr>
					<tr><td><span class='halflings halflings-tag'></span><b>Name:</b></td><td><input type='text' value='<?=$_POST['name'];?>' name='name' required></td></tr>
					<tr><td><span class='halflings halflings-user'></span><b>User:</b></td><td><input type='text' value='<?=$_POST['user'];?>' name='user' required></td></tr>
					<tr><td><span class='halflings halflings-glyph-lock'></span><b>Pass:</b></td><td><input type='text' name='pass' required></td></tr>
					<tr><td><span class='halflings halflings-transfer'></span><b>SQL type:</b></td><td>
						<select name='type' <?=$ini['options']['sqltype'];?>>
							<?=$outsqllist;?>
						</select>
					</td></tr>
					<tr><td><span class='halflings halflings-compressed'></span><b>Cache:</b></td><td>
						<select name='cache' <?=$ini['options']['cachetype'];?>>
							<?=$outcachelist;?>
						</select>
					</td></tr>
					<tr><td></td><td><input class='btn btn-success' type='submit' value='Install'></td></tr>
				</table>
			</form>
			<?elseif($endstatus===1):?>
			<table class='table table-striped'>
				<tr><td><span class='glyphicons glyphicons-old-man'></span><kbd>Super user</kbd></td><td></td></tr>
				<tr><td><b>User:</b></td><td><?=$_POST['user-new-name'];?></td></tr>
				<tr><td><b>Email:</b></td><td><?=$_POST['user-new-mail'];?></td></tr>
				<tr><td><b>Pass:</b></td><td>****</td></tr>
				<tr><td><b>Retype pass:</b></td><td>****</td></tr>
				<tr><td><b>DB Host:</b></td><td><?=$_POST['host'];?></td></tr>
				<tr><td><b>DB Name:</b></td><td><?=$_POST['name'];?></td></tr>
				<tr><td><b>DB User:</b></td><td><?=$_POST['user'];?></td></tr>
				<tr><td><b>DB Pass:</b></td><td>****</td></tr>
				<tr><td><b>SQL type:</b></td><td><?=$_POST['type'];?></td></tr>
				<tr><td></td><td><span class='glyphicons glyphicons-circle-ok'></span><p class='text-success'><b>Success</b></p></td></tr>
			</table>
			<?elseif($endstatus===2):?>
			<p class='bg-danger'><span class='glyphicons glyphicons-warning-sign'></span>PHP must be in version >= <mark>5.3</mark>!</p>
			<?elseif($endstatus===3):?>
			<p class='bg-danger'><span class='glyphicons glyphicons-warning-sign'></span><?=$ini['modal']['message'];?></p>
			<?elseif($endstatus===4):?>
			<p class='bg-danger'><span class='glyphicons glyphicons-warning-sign'></span><?=$ini['modal']['perm_message'];?></p>
			<?endif;?>
		</div>
	</body>
</html>