<?php
/*~ cache.php
.---------------------------------------------------------------------------.
|  Software: SencilloCache                                                  |
|   Version: 2014.003                                                       |
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
'---------------------------------------------------------------------------'
~*/
if(get_idkey2()==1)
{
	require("./fw_modules/mod_identificator.php");
}
if(($perm==1111) && ($_GET["pageid"]=="index6"))
{
	echo("<br />");
	for($i=0;$i<=$max_system_modules;$i++)
	{
		$ary=$all_data["admin"][$i];
		$admin_box=$d2;
	}
}
for($i=0;$i<=$num_centerboxid;$i++)
{
	$ary=$all_data["center"][$i];
	$d1=$ary;
	$user_box=$d1;
}
for($i=0;$i<=$num_leftboxid;$i++){echo($all_data["left"][$i]);}
for($i=0;$i<=$num_leftboxid;$i++){echo($all_data["right"][$i]);}
echo($all_data["foot"][0]);echo($footbox);
?>
