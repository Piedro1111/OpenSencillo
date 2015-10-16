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

$PHPversion=explode(".",phpversion());
echo("<body><div class='container' style='width:600px;border:1px solid gray;padding:0px;'><form method='post' action='http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."?install=true'><table class='table table-striped'>");
echo("<tr><td><span class='glyphicons glyphicons-circle-info'></span><kbd>About ".$afterBootUp[0]->info['FWK']."</kbd></td><td></td></tr>");
echo("<tr><td><b>System:</b></td><td>".$afterBootUp[0]->info['FWK']."</td></tr>");
echo("<tr><td><b>By:</b></td><td>".$afterBootUp[0]->info['CPY']."</td></tr>");
echo("<tr><td><b>Homepage:</b></td><td><a href='http://www.opensencillo.com' target='_blank'>opensencillo.com</a></td></tr>");
echo("<tr><td><b>PHP:</b></td><td>".$PHPversion[0].".".$PHPversion[1]."</td></tr>");
echo("<tr><td><b>DB charset:</b></td><td>UTF-8</td></tr>");
echo("<tr><td><b>System charset:</b></td><td>UTF-8</td></tr>");
if(($_GET['install']!='true')||($_POST['user-new-pass']!=$_POST['user-rtp-pass'])||(empty($_POST['user-new-pass'])))
{
	if(($PHPversion[0]>=5)&&($PHPversion[1]>=3))
	{
		echo("<tr><td><span class='glyphicons glyphicons-old-man'></span><kbd>Superuser</kbd></td><td></td></tr>");
		echo("<tr><td><b>User:</b></td><td><input type='text' value='".$_POST['user-new-name']."' name='user-new-name' required></td></tr>");
		echo("<tr><td><b>Email:</b></td><td><input type='email' value='".$_POST['user-new-mail']."' name='user-new-mail' required></td></tr>");
		echo("<tr class='failgroupe1'><td><b>Pass:</b></td><td><input type='password' value='password1' name='user-new-pass' required></td></tr>");
		echo("<tr class='failgroupe1'><td><b>Retype pass:</b></td><td><input type='password' value='password2' name='user-rtp-pass' required></td></tr>");
		echo("<tr><td><b>Permission:</b></td><td><select name='perm' disabled>
	                                                <option value='admin' selected>Admin</option>
	                                           </select></td></tr>");
		
		echo("<tr><td><span class='glyphicons glyphicons-database'></span><kbd>Database</kbd></td><td></td></tr>");
		echo("<tr><td><span class='halflings halflings-hdd'></span><b>Host:</b></td><td><input type='text' value='".$_POST['host']."' name='host' required></td></tr>");
		echo("<tr><td><span class='halflings halflings-tag'></span><b>Name:</b></td><td><input type='text' value='".$_POST['name']."' name='name' required></td></tr>");
		echo("<tr><td><span class='halflings halflings-user'></span><b>User:</b></td><td><input type='text' value='".$_POST['user']."' name='user' required></td></tr>");
		echo("<tr><td><span class='halflings halflings-glyph-lock'></span><b>Pass:</b></td><td><input type='text' name='pass' required></td></tr>");
		echo("<tr><td><span class='halflings halflings-transfer'></span><b>SQL type:</b></td><td><select name='type'>
	                                                <option value='mysql' selected>MySQL</option>
	                                                <option value='mariasql'>MariaDB</option>
													<option value='othersql'>Other SQL</option>
	                                           </select></td></tr>");
		echo("<tr><td><span class='halflings halflings-compressed'></span><b>Cache:</b></td><td><select name='cache'>
	                                                <option value='1'>Allow</option>
	                                                <option value='0' selected>Disallow</option>
	                                           </select></td></tr>");
		echo("<tr><td></td><td><input class='btn btn-success' type='submit' value='Install'></td></tr>");
	}
	else
	{
		echo("</table><p class='bg-danger'><span class='glyphicons glyphicons-warning-sign'></span>PHP must be in version >= <mark>5.3</mark>!</p><table>");
	}
}
else
{
	header('Location: http://'.$_SERVER['SERVER_NAME']);
	echo("<tr><td><span class='glyphicons glyphicons-old-man'></span><kbd>Super user</kbd></td><td></td></tr>");
	echo("<tr><td><b>User:</b></td><td>".$_POST['user-new-name']."</td></tr>");
	echo("<tr><td><b>Email:</b></td><td>".$_POST['user-new-mail']."</td></tr>");
	echo("<tr><td><b>Pass:</b></td><td>****</td></tr>");
	echo("<tr><td><b>Retype pass:</b></td><td>****</td></tr>");

	echo("<tr><td><b>Host:</b></td><td>".$_POST['host']."</td></tr>");
	echo("<tr><td><b>Name:</b></td><td>".$_POST['name']."</td></tr>");
	echo("<tr><td><b>User:</b></td><td>".$_POST['user']."</td></tr>");
	echo("<tr><td><b>Pass:</b></td><td>****</td></tr>");
	echo("<tr><td><b>SQL type:</b></td><td>".$_POST['type']."</td></tr>");
	echo("<tr><td></td><td><span class='glyphicons glyphicons-circle-ok'></span><p class='text-success'><b>Success</b></p></td></tr>");
}
echo('</table></form>');
if(($_POST['user-new-pass']!==$_POST['user-rtp-pass'])&&(!empty($_POST['user-new-pass']))&&(!empty($_POST['user-rtp-pass'])))
{
echo('<!-- Modal -->
<script type="text/javascript">
$(document).ready(function(){
	$("#myModal,.modal-dialog,.modal-content").fadeIn();
	$(".failgroupe1").css("background-color","red");
	$(".hide-dialog").click(function(){
		$("#myModal").fadeOut();
	});
});
</script>
<style>
#myModal {
	background-color: rgba(255,255,255,0.5);
}
</style>
<div id="myModal" class="modal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="hide-dialog close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Password retype error</h4>
			</div>
			<div class="modal-body">
				<p>An error has occurred in section Superuser!</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="hide-dialog btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>');
}
echo('</div></body></html>');
?>
