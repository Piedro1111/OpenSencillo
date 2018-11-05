<?php
$pihome = new pihome;
$this->config_mod($this->protocol,$this->url,$this->defaultcfg[2]);
?>
					<div class="">
						<div class="clearfix"></div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_title">
										<h2>Ext HDD switch</h2>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">
										<br>
										<form class="form-horizontal form-label-left">
											<div class="row">
												<div class="col-lg-6">
													<div class="form-group">
														<label class="control-label col-lg-6 col-md-6 col-sm-6 col-xs-12">Power</label>
														<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
															<select id="exthdd" class="form-control">
																<option <?=($pihome->ExtHDDcontent==1?'selected ':'');?>value="1">ON</option>
																<option <?=($pihome->ExtHDDcontent==0?'selected ':'');?>value="0">OFF</option>
															</select>
														</div>
													</div>
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
