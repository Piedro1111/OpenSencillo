<?php
echo $this->seo->save();
$wmsg=$this->readMsgLog();
?>
	<body class="nav-md">
		<div class="container body">
			<div class="main_container">
				<div class="col-md-3 left_col">
					<div class="left_col scroll-view">
						<div class="navbar nav_title" style="border: 0;">
							<a href="#admin-system" class="site_title"><span>Admin System</span></a>
						</div>
						<div class="clearfix"></div>
						<!-- menu prile quick info -->
						<div class="profile">
							<div class="profile_pic">
							</div>
							<?if($logman->checkSession()):?>
							<div class="profile_info">
								<span><?=$this->usertype;?></span>
								<h2><?=$_SESSION['login'];?></h2>
							</div>
							<?endif;?>
						</div>
						<div class="clearfix"></div>
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
	if($menuitem['perm']>1000)
	{
		echo '<li><a href="'.$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->urlprefix.(($this->urlprefix!='')?'/':'').$menuitem['link'].'"><i class="'.$menuitem['icon'].'"></i> '.$menuitem['name'].'</a>
		</li>';
	}
}
?>
								</ul>
							</div>
						</div>
						<!-- /sidebar menu -->
						<?endif;?>
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
										<!--<img src="<?=$this->js;?>/images/pi2.png" alt="">--><?=$_SESSION['login'];?>
										<?if($logman->checkSession()):?><span class=" fa fa-angle-down"></span><?endif;?>
									</a>
									<?if($logman->checkSession()):?>
									<ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
										<li><a href="http://<?=$_SERVER['SERVER_NAME'].$this->port.'/'.$this->urlprefix.(($this->urlprefix!='')?'/':'');?>profile?u=<?=$logman->getSessionData('userid');?>"><i class="fa fa-user pull-right"></i> Profile</a>
										</li>
										<li><a href="http://<?=$_SERVER['SERVER_NAME'].$this->port.'/'.$this->urlprefix.(($this->urlprefix!='')?'/':'');?>logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
										</li>
									</ul>
									<?endif;?>
								</li>
								<?if($logman->checkSession()):?>
								<li role="presentation" class="dropdown">
									<a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
										<i class="fa fa-envelope-o"></i>
										<?if($wmsg['ctr'][0]['count']>0):?><span class="badge bg-green"><?=$wmsg['ctr'][0]['count'];?></span><?endif;?>
									</a>
									<?if($wmsg['ctr'][0]['count']>0):?>
									<ul id="menu1" class="dropdown-menu list-unstyled msg_list animated fadeInDown" role="menu">
<?php
foreach($wmsg['data'] as $item)
{
	if($logman->checkSession())
	{
		echo '<li>
			<a>
				<span>
					<span>Event #'.$item['id'].'</span>
					<span class="time">'.$item['datetime'].'</span>
				</span>
				<span class="message">
					'.$item['message'].'
				</span>
			</a>
		</li>';
	}
}
?>
									</ul>
									<?endif;?>
								</li>
								<?endif;?>
							</ul>
						</nav>
					</div>
				</div>
				<!-- /top navigation -->
				<!-- page content -->
				<div class="right_col" role="main">