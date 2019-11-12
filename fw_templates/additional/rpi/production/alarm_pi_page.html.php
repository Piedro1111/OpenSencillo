<?php
$pihome = new pihome;
$this->config_mod($this->protocol,$this->url,$this->defaultcfg[2]);
if(class_exists('iotboard'))
{
	$iotboard=new iotboard;
	file_get_contents("http://localhost:5000/private/api/v1.0/server/email/".$iotboard->showKey());
}
?>
					<div class="">
						<div class="clearfix"></div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_title">
										<h2>Alarm</h2>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">
										<div class="row">
											<div class="col-md-12">
												<div class="image view view-first">
													<img style="width: 100%; display: block;" src="/<?=$this->url;?>/fw_media/media_imgs/email_attachments/new.png" alt="latest alarm">
												</div>
											</div>
										</div>
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
