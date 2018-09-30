					<div class="">
						<div class="clearfix"></div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_title">
										<h2>Registration</h2>
										<ul class="nav navbar-right panel_toolbox">
										</ul>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">
										<br>
										<form class="form-horizontal form-label-left">
											<div class="item form-group sencillo-email-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">E-mail <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input id="email" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="email" placeholder="" required="required" type="text">
													<ul class="parsley-errors-list sencillo-errors-list filled" style="display:none">
														<li class="parsley-required ereg-err-email">Email value is required.</li>
													</ul>
												</div>
											</div>
											<div class="item form-group sencillo-pass-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pass">Password <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input id="pass" type="password" name="pass" data-validate-length="6,25" required="required" class="form-control col-md-7 col-xs-12">
													<ul class="parsley-errors-list sencillo-errors-list filled" style="display:none">
														<li class="parsley-required ereg-err-pass">This value is required.</li>
													</ul>
												</div>
											</div>
											<div class="item form-group sencillo-rtp-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rtppass">Retype password <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input id="rtppass" type="password" name="rtppass" data-validate-length="6,25" required="required" class="form-control col-md-7 col-xs-12">
													<ul class="parsley-errors-list sencillo-errors-list filled" style="display:none">
														<li class="parsley-required ereg-err-rtp">This value is required.</li>
													</ul>
												</div>
											</div>
											<div class="ln_solid"></div>
											<div class="form-group">
												<div class="col-md-6 col-md-offset-3">
													<button id="send_ereg" type="button" class="btn btn-success">Submit</button>
													<button type="button" data-url="" class="btn btn-link open-button">Login</button>
													<button type="button" data-url="forgot" class="btn btn-link open-button">Forgot password</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- footer content -->
					<footer>
						<div class="copyright-info">
							<p class="pull-right">Powered by <a href="https://opensencillo.com">OpenSencillo</a>
							</p>
						</div>
						<div class="clearfix"></div>
					</footer>
					<!-- /footer content -->
				</div>
				<!-- /page content -->
			</div>
		</div>
		<div id="custom_notifications" class="custom-notifications dsp_none">
			<ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
			</ul>
			<div class="clearfix"></div>
			<div id="notif-group" class="tabbed_notifications"></div>
		</div>
		<!--<script src="<?=$this->js;?>/js/bootstrap.min.js"></script>-->
		<!-- bootstrap progress js -->
		<script src="<?=$this->js;?>/js/progressbar/bootstrap-progressbar.min.js"></script>
		<script src="<?=$this->js;?>/js/nicescroll/jquery.nicescroll.min.js"></script>
		<!-- icheck -->
		<script src="<?=$this->js;?>/js/icheck/icheck.min.js"></script>
		<script src="<?=$this->js;?>/js/custom.js"></script>
		<!-- pace -->
		<script src="<?=$this->js;?>/js/pace/pace.min.js"></script>
	</body>
</html>
