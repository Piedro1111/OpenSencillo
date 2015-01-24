<!--------------------------------------------------------------------------.
|  Software: Sencillo Default Theme                                         |
|   Version: 2015.003                                                       |
|   Contact: ph@mastery.sk                                                  |
| ------------------------------------------------------------------------- |
|    Author: Bc. Peter Horváth (original founder)                           |
| Copyright (c) 2015, Bc. Peter Horváth. All Rights Reserved.               |
| ------------------------------------------------------------------------- |
|   License: Distributed under the General Public License (GPL)             |
|            http://www.gnu.org/copyleft/gpl.html                           |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'--------------------------------------------------------------------------->
<?php
$PHPversion=explode(".",phpversion());
echo("<body><div class='container' style='width:600px;border:1px solid gray;padding:0px;'><form method='post' action='http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."?install=true'><table class='table table-striped'>");
echo("<tr><td><span class='glyphicons glyphicons-circle-info'></span><kbd>About ".$afterBootUp[0]->info['FWK']."</kbd></td><td></td></tr>");
echo("<tr><td><b>System:</b></td><td>".$afterBootUp[0]->info['FWK']."</td></tr>");
echo("<tr><td><b>By:</b></td><td>".$afterBootUp[0]->info['CPY']."</td></tr>");
echo("<tr><td><b>Homepage:</b></td><td><a href='http://www.opensencillo.com' target='_blank'>opensencillo.com</a></td></tr>");
echo("<tr><td><b>PHP:</b></td><td>".$PHPversion[0].".".$PHPversion[1]."</td></tr>");
echo("<tr><td><b>DB charset:</b></td><td>UTF-8</td></tr>");
echo("<tr><td><b>System charset:</b></td><td>UTF-8</td></tr>");
echo("<tr><td><span class='glyphicons glyphicons-database'></span><kbd>Database</kbd></td><td></td></tr>");
if($_GET['install']!='true')
{
	if(($PHPversion[0]>=5)&&($PHPversion[1]>=3))
	{
		echo("<tr><td><span class='halflings halflings-hdd'></span><b>Host:</b></td><td><input type='text' name='host'></td></tr>");
		echo("<tr><td><span class='halflings halflings-tag'></span><b>Name:</b></td><td><input type='text' name='name'></td></tr>");
		echo("<tr><td><span class='halflings halflings-user'></span><b>User:</b></td><td><input type='text' name='user'></td></tr>");
		echo("<tr><td><span class='halflings halflings-glyph-lock'></span><b>Pass:</b></td><td><input type='text' name='pass'></td></tr>");
		echo("<tr><td><span class='halflings halflings-transfer'></span><b>SQL type:</b></td><td><select name='type'>
	                                                <option value='mysql' selected>MySQL</option>
	                                                <option value='mariasql'>MariaSQL</option>
	                                                <option value='pgsql'>PgSQL</option>
													<option value='none'>none</option>
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
	echo("<tr><td><b>Host:</b></td><td>".$_POST['host']."</td></tr>");
	echo("<tr><td><b>Name:</b></td><td>".$_POST['name']."</td></tr>");
	echo("<tr><td><b>User:</b></td><td>".$_POST['user']."</td></tr>");
	echo("<tr><td><b>Pass:</b></td><td>****</td></tr>");
	echo("<tr><td><b>SQL type:</b></td><td>".$_POST['type']."</td></tr>");
	echo("<tr><td></td><td><span class='glyphicons glyphicons-circle-ok'></span><p class='text-success'><b>Success</b></p></td></tr>");
}
echo("</table></form></div></body></html>");
?>