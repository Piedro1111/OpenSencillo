<?$t=$translate;?>
<body>
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
    <div id="login">
    	<h1><?=$t->translate('Login')?></h1>
        <form action="<?=LOGIN_ACTION;?>" method="POST">
        	<p><input type="text" name="email" value="<?=$t->translate('User');?>" required></p>
        	<p><input type="password"  name="pass" value="<?=$t->translate('Password');?>" required></p>
        	<p><input type="submit" value="<?=$t->translate('Login');?>"></p>
        </form>
      <p><a href="<?='http://'.$_SERVER['SERVER_NAME'].'/registration';?>"><?=$t->translate('Registration');?></a></p>
      <p><?=LOGIN_ERRMSG;?></p>
    </div>
</body>
</html>