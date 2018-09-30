					<div class="">
						<div class="clearfix"></div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div>
										<style>
										table {
											width: 100%;
											border: 1px solid #ccc;
											background: #fff;
											padding: 1px;
										}
										td, th {
											border: 1px solid #FFF;
											font-family: Verdana, sans-serif;
											font-size: 12px;
											padding:4px 8px;
										}
										th {
											background-color: rgba(52, 73, 94, 0.94);
										}
										.e, .v, .vr {
											color: #333;
											font-family: Verdana, Helvetica, sans-serif;
											font-size: 11px;
										}
										.e {
											background-color: #eee;
										}
										.h {
											background-color: rgba(52, 73, 94, 0.94);
											color: #fff;
										}
										.v {
											background-color: #F1F1F1;
											-ms-word-break: break-all;
											word-break: break-all;
											word-break: break-word;
											-webkit-hyphens: auto;
											-moz-hyphens: auto;
											hyphens: auto;
										}
										img {
											display:none;
										}
										</style>
										<?php
										ob_start();
										phpinfo();
										$pinfo = ob_get_contents();
										ob_end_clean();
										 
										$pinfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$pinfo);
										echo $pinfo;
										?>
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
