<?php
$ini = parse_ini_file('./fw_headers/install.ini',true);
$t=$translate;
$afterBootUp=array();
$afterBootUp[0]=$core->coreSencillo;
?>
	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="http://www.opensencillo.com"><?=$t->translate('OpenSencillo');?></a>
				</div>
				<div>
					<?if($ini['first_screen']['menu']=="true"):?>
					<ul class="nav navbar-nav">
						<li class="active"><a href="http://www.opensencillo.com"><?=$t->translate('Homepage');?></a></li>
						<li><a href="http://github.com/Piedro1111/OpenSencillo/archive/master.zip"><?=$t->translate('Download');?></a></li>
						<li><a href="http://www.opensencillo.com/installation-2015-003/"><?=$t->translate('Installation');?></a></li>
						<li><a href="<?=$ini['first_screen']['docs'];?>"><?=$t->translate('Documentation');?></a></li>
						<li><a href="https://github.com/Piedro1111/OpenSencillo">GitHub</a></li>
					</ul>
					<?endif;?>
				</div>
			</div>
		</nav>
		<div style="padding-top: 100px;" class="container">
			<div class="row">
				<div class="col-sm-12">
					<h1><?=$t->translate('It works');?></h1>
					<div class="alert alert-success">
						<strong><?=$t->translate('Success');?>!</strong> <?=$t->translate('Write your PHP code to file yourcode.php');?>.
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<ul class="list-group">
						<li class="list-group-item"><strong>Name:</strong> <?=$afterBootUp[0]->info['NME'];?></li>
						<li class="list-group-item"><strong>Licence:</strong> GNU/GPL</li>
						<li class="list-group-item"><strong>Type:</strong> Framework</li>
						<li class="list-group-item"><strong>Category:</strong> OpenSource</li>
						<li class="list-group-item"><strong>Language:</strong> PHP 5.3+, JQUERY, HTML5</li>
						<li class="list-group-item"><strong>Release:</strong> <?=$afterBootUp[0]->info['VSN'];?></li>
						<li class="list-group-item"><strong>Build:</strong> <?=$afterBootUp[0]->info['DTC'];?></li>
						<li class="list-group-item"><strong>By:</strong> <?=$afterBootUp[0]->info['CPY'];?></li>
						<li class="list-group-item"><strong>Homepage:</strong> <a href="<?=$afterBootUp[0]->info['HPE'];?>"><?=$afterBootUp[0]->info['HPE'];?></a></li>
						<li class="list-group-item"><strong>Features:</strong> File management, File Convertors, Database management, SEO, Session & Cookies management, Hash subsystem, Translates JSON file, Unit Testing, …</li>
					</ul>
				</div>
			</div>
		</div>
	</body>
</html>