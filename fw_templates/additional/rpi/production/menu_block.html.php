	<body class="nav-md">
		<div class="container body">
			<div class="main_container">
				<div class="col-md-3 left_col">
					<div class="left_col scroll-view">
						<div class="navbar nav_title" style="border: 0;">
							<a href="<?='http://'.$_SERVER['SERVER_NAME'].$this->port.'/pihome/';?>" class="site_title"><i class="fa fa-linux"></i> <span>piHome</span></a>
						</div>
						<div class="clearfix"></div>
						<!-- menu prile quick info -->
						<div class="profile">
							<div class="profile_pic">
								<?if($logman->checkSession()):?><img src="<?=$this->js;?>/images/pi2.png" alt="raspberry image" class="img-circle profile_img"><?endif;?>
							</div>
							<?if($logman->checkSession()):?>
							<div class="profile_info">
								<span><?=$this->usertype;?></span>
								<h2><?=$_SESSION['login'];?></h2>
							</div>
							<?endif;?>
						</div>
						<!-- /menu prile quick info -->
						<br />
						<!-- sidebar menu -->
						<?if($_SESSION['perm']>=1000):?>
						<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
							<div class="menu_section">
								<h3>Main Menu</h3>
								<ul class="nav side-menu">
<?php
foreach($this->mainmenu as $menuitem)
{
	echo '<li><a href="'.$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->urlprefix.'/'.$menuitem['link'].'"><i class="'.$menuitem['icon'].'"></i> '.$menuitem['name'].'</a>
		</li>';
}
?>
									<?if($_SESSION['perm']>=1110):?>
									<li><a><i class="fa fa-home"></i> GPIO <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu" style="display: none">
											<li><a href="<?='http://'.$_SERVER['SERVER_NAME'].$this->port.'/pihome/gpio';?>">Config</a>
											</li>
											<li><a id="gpio-all-reset">Reset all</a>
											</li>
										</ul>
									</li>
									<?endif;?>
								</ul>
							</div>
						</div>
						<!-- /sidebar menu -->
						<!-- /menu footer buttons -->
						<div class="sidebar-footer hidden-small">
							<a data-toggle="tooltip" data-placement="top" title="Settings">
								<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
							</a>
							<a id="status" data-toggle="tooltip" data-placement="top" title="Status">
								<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							</a>
							<a id="restart" data-toggle="tooltip" data-placement="top" title="Restart">
								<span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>
							</a>
							<a id="shutdown" data-toggle="tooltip" data-placement="top" title="Shutdown">
								<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
							</a>
						</div>
						<?endif;?>
						<!-- /menu footer buttons -->
					</div>
				</div>
				<!-- top navigation -->
				<div class="top_nav">
					<div class="nav_menu">
						<nav class="" role="navigation">
							<div class="nav toggle">
								<a id="menu_toggle"><i class="fa fa-bars"></i></a>
							</div>
							<ul class="nav navbar-nav navbar-right">
								<li class="">
									<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<img src="<?=$this->js;?>/images/pi2.png" alt=""><?=$_SESSION['login'];?>
										<?if($logman->checkSession()):?><span class=" fa fa-angle-down"></span><?endif;?>
									</a>
									<?if($logman->checkSession()):?>
									<ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
										<li><a href="http://<?=$_SERVER['SERVER_NAME'].$this->port;?>/pihome/logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
										</li>
									</ul>
									<?endif;?>
								</li>
								<?if($logman->checkSession()):?>
								<li role="presentation" class="dropdown">
									<a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
										<i class="fa fa-envelope-o"></i>
										<span class="badge bg-green">0</span>
									</a>
									<ul id="menu1" class="dropdown-menu list-unstyled msg_list animated fadeInDown" role="menu">
										<li>
											<a>
												<!--<span class="image">
													<img src="images/img.jpg" alt="Profile Image" />
												</span>-->
												<span>
													<span>Welcome <?=$_SESSION['login'];?></span>
													<span class="time"><?=$_SESSION['start'];?></span>
												</span>
												<span class="message">
													PiHome system temperature is <?=$this->CPUtemperature;?>°C. No aditional data.
												</span>
											</a>
										</li>
									</ul>
								</li>
								<?endif;?>
							</ul>
						</nav>
					</div>
				</div>
				<!-- /top navigation -->
				<!-- page content -->
				<div class="right_col" role="main">
					<?if($logman->checkSession()):?>
					<!-- top tiles -->
					<div class="row tile_count">
					  <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
						<div class="left"></div>
						<div class="right">
						  <span class="count_top"><i class="fa fa-fire"></i> piHome CPU temp.</span>
						  <div class="count"><span class="<?=(($this->CPUtemperature<60)?' green':' red');?>"><?=$this->CPUtemperature;?></span>°C</div>
						  <!--<span class="count_bottom"><i class="green">4% </i> From last Week</span>-->
						</div>
					  </div>
					  <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
						<div class="left"></div>
						<div class="right">
						  <span class="count_top"><i class="fa fa-fire"></i> piHome Media CPU temp.</span>
						  <div class="count"><span class="<?=(($this->playerCPUtemperature['temp']<60)?' green':' red');?>"><?=$this->playerCPUtemperature['temp'];?></span>°C</div>
						  <!--<span class="count_bottom"><i class="green">4% </i> From last Week</span>-->
						</div>
					  </div>
					  <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
						<div class="left"></div>
						<div class="right">
						  <span class="count_top"><i class="fa fa-tint"></i> Condensation</span>
						  <div class="count"><span class="<?=((($this->CondensationLVL==0)&&($this->Condensation['date']==date('Y-m-d')))?' green':' red');?>"><?=(($this->Condensation['date']==date('Y-m-d'))?$this->CondensationSTS:'Error');?></span></div>
						</div>
					  </div>
					  <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
						<div class="left"></div>
						<div class="right">
						  <span class="count_top"><i class="fa fa-cloud"></i> Ext HDD</span>
						  <div class="count"><span id="exthddstatus" class="<?=(($this->ExtHDDcontent==0)?' green':' red');?>"><?=(($this->HDDerr==0)?(($this->ExtHDDcontent==0)?'OFF':'ON'):'Error');?></span></div>
						</div>
					  </div>
					  <!--<div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
						<div class="left"></div>
						<div class="right">
						  <span class="count_top"><i class="fa fa-user"></i> Total Males</span>
						  <div class="count green">2,500</div>
						  <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
						</div>
					  </div>
					  <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
						<div class="left"></div>
						<div class="right">
						  <span class="count_top"><i class="fa fa-user"></i> Total Females</span>
						  <div class="count">4,567</div>
						  <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i> From last Week</span>
						</div>
					  </div>
					  <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
						<div class="left"></div>
						<div class="right">
						  <span class="count_top"><i class="fa fa-user"></i> Total Collections</span>
						  <div class="count">2,315</div>
						  <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
						</div>
					  </div>
					  <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
						<div class="left"></div>
						<div class="right">
						  <span class="count_top"><i class="fa fa-user"></i> Total Connections</span>
						  <div class="count">7,325</div>
						  <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
						</div>
					  </div>-->
					</div>
					<!-- /top tiles -->
					<?endif;?>