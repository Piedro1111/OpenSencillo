<?$t=$translate;?>
<body>
<!--------------------------------------------------------------------------.
|  Software: Sencillo Default Theme                                         |
|   Version: 2015.105                                                       |
|   Contact: ph@mastery.sk                                                  |
| ------------------------------------------------------------------------- |
|    Author: Bc. Peter Horváth (original founder)                           |
| Copyright (c) 2014-<?=date('Y');?>, Bc. Peter Horváth. All Rights Reserved.          |
| ------------------------------------------------------------------------- |
|   License: Distributed under the General Public License (GPL)             |
|            http://www.gnu.org/licenses/gpl-3.0.html                       |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'--------------------------------------------------------------------------->
    <div id="login" class="container">
		<div class="row">
			<div class="col-sm-4"></div>
			<div class="col-sm-4">
				<h1><?=$t->translate('Login')?></h1>
				<form action="<?=LOGIN_ACTION;?>" method="POST">
					<fieldset class="clearfix">
						<div class="row">
							<p><input type="text" name="email" placeholder="<?=$t->translate('User');?>" required></p>
						</div>
						<div class="row">
							<p><input type="password" name="pass" placeholder="<?=$t->translate('Password');?>" required></p>
						</div>
						<div class="row">
							<p><input type="submit" value="<?=$t->translate('Login');?>"></p>
						</div>
					</fieldset>
				</form>
				<p><a href="<?='http://'.$_SERVER['SERVER_NAME'].'/registration';?>"><?=$t->translate('Registration');?></a></p>
				<p><?=LOGIN_ERRMSG;?></p>
			</div>
			<div class="col-sm-4"></div>
		</div>
	</div>
</body>
</html>