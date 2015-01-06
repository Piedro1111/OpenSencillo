<!--------------------------------------------------------------------------.
|  Software: Sencillo Default Theme                                         |
|   Version: 2015.001                                                       |
|   Contact: ph@mastery.sk                                                  |
| ------------------------------------------------------------------------- |
|    Author: Bc. Peter Horváth (original founder)                           |
| Copyright (c) 2014, Bc. Peter Horváth. All Rights Reserved.               |
| ------------------------------------------------------------------------- |
|   License: Distributed under the General Public License (GPL)             |
|            http://www.gnu.org/copyleft/gpl.html                           |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'--------------------------------------------------------------------------->
<?php
$PHPversion=explode(".",phpversion());
echo("<body><form method='post' action='http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."?install=true'><table>");
echo("<tr><td><b>System:</b></td><td>".$afterBootUp[0]->info['FWK']."</td></tr>");
echo("<tr><td><b>Installation mode:</b></td><td>default</td></tr>");
echo("<tr><td><b>By:</b></td><td>".$afterBootUp[0]->info['CPY']."</td></tr>");
echo("<tr><td><b>PHP:</b></td><td>".$PHPversion."</td></tr>");
echo("<tr><td><b>DB charset:</b></td><td>UTF-8</td></tr>");
echo("<tr><td><b>System charset:</b></td><td>UTF-8</td></tr>");
echo("<tr><td><b>Database</b></td><td></td></tr>");
if(($_GET['install']!='true')&&($PHPversion[0]>=5))
{
	echo("<tr><td><b>Host:</b></td><td><input type='text' name='host'></td></tr>");
	echo("<tr><td><b>Name:</b></td><td><input type='text' name='name'></td></tr>");
	echo("<tr><td><b>User:</b></td><td><input type='text' name='user'></td></tr>");
	echo("<tr><td><b>Pass:</b></td><td><input type='text' name='pass'></td></tr>");
	echo("<tr><td><b>SQL type:</b></td><td><select name='type'>
                                                <option value='mysql' selected>MySQL</option>
                                                <option value='mariasql'>MariaSQL</option>
                                                <option value='pgsql'>PgSQL</option>
                                           </select></td></tr>");
	echo("<tr><td><b>Cache:</b></td><td><select name='cache'>
                                                <option value='1' selected>Allow</option>
                                                <option value='0'>Disallow</option>
                                           </select></td></tr>");
	echo("<tr><td></td><td><input type='submit' value='Install'></td></tr>");
}
else
{
	echo("<tr><td><b>Host:</b></td><td>".$_POST['host']."</td></tr>");
	echo("<tr><td><b>Name:</b></td><td>".$_POST['name']."</td></tr>");
	echo("<tr><td><b>User:</b></td><td>".$_POST['user']."</td></tr>");
	echo("<tr><td><b>Pass:</b></td><td>****</td></tr>");
	echo("<tr><td><b>SQL type:</b></td><td>".$_POST['type']."</td></tr>");
	echo("<tr><td></td><td><b>Success</b></td></tr>");
}
echo("</table></form></body>");
?>