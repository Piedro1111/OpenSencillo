					<div class="">
						<div class="clearfix"></div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_title">
										<h2>Forgot password</h2>
										<ul class="nav navbar-right panel_toolbox">
										</ul>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">
										<br>
										<form class="form-horizontal form-label-left">
											<div class="item form-group sencillo-code-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Code <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input id="code" class="form-control col-md-7 col-xs-12" name="code" required="required" type="text">
													<ul class="parsley-errors-list sencillo-errors-list filled" style="display:none">
														<li class="parsley-required ereg-err-code">Code is required.</li>
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
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pass">Retype password <span class="required">*</span>
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
													<button id="send_newpass" type="button" class="btn btn-success">Change password</button>
													<button type="button" data-url="" class="btn btn-link open-button">Login</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
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
