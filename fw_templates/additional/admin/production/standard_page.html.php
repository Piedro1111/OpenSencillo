					<div class="">
						<div class="clearfix"></div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div>
										<?php
										$pdata = $this->getPageContentByUrl();
										//var_dump($pdata);
										foreach($pdata as $key=>$val)
										{
											$pdata_val = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$val['article']);
											echo $pdata_val;
										}
										?>
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
