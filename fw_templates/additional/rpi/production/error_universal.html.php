	<body class="nav-md">
		<div class="container body">
			<div class="main_container">
				<!-- page content -->
				<div class="col-md-12">
					<div class="col-middle">
						<div class="text-center text-center">
							<h1 class="error-number"><?=$template->errorCode();?></h1>
							<h2><?=$template->errorBasicMsg();?></h2>
							<p><?=$template->errorLongMsg();?> <a href="#">Report this?</a>
							</p>
							<div class="mid_center">
								<h3>Search</h3>
								<form>
									<div class="col-xs-12 form-group pull-right top_search">
										<div class="input-group">
											<input type="text" class="form-control" placeholder="Search for...">
											<span class="input-group-btn">
												<button class="btn btn-default" type="button">Go!</button>
											</span>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /page content -->
			</div>
			<!-- footer content -->
		</div>
		<div id="custom_notifications" class="custom-notifications dsp_none">
			<ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
			</ul>
			<div class="clearfix"></div>
			<div id="notif-group" class="tabbed_notifications"></div>
		</div>
		<script src="<?=$template->path();?>js/bootstrap.min.js"></script>
		<!-- bootstrap progress js -->
		<script src="<?=$template->path();?>js/progressbar/bootstrap-progressbar.min.js"></script>
		<script src="<?=$template->path();?>js/nicescroll/jquery.nicescroll.min.js"></script>
		<!-- icheck -->
		<script src="<?=$template->path();?>js/icheck/icheck.min.js"></script>
		<script src="<?=$template->path();?>js/custom.js"></script>
		<!-- pace -->
		<script src="<?=$template->path();?>js/pace/pace.min.js"></script>
		<!-- /footer content -->
	</body>
</html>
