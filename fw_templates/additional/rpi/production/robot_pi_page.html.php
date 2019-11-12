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
										<h2>piRobot Modes</h2>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">
										<br>
										<form class="form-horizontal form-label-left">
											<div class="row">
												<div class="col-lg-6">
													<div class="form-group">
														<label class="control-label col-lg-6 col-md-6 col-sm-6 col-xs-12">Robot's IP</label>
														<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
															<input id="pirobot_ip" name="pirobot_ip" class="form-control" value="<?=$_GET['pirobot_ip'];?>">
														</div>
													</div>
												</div>
											</div>
											<?if(filter_var($_GET['pirobot_ip'], FILTER_VALIDATE_IP)):?>
											<div class="row">
												<div class="col-lg-6">
													<div class="form-group">
														<label class="control-label col-lg-6 col-md-6 col-sm-6 col-xs-12">Action</label>
														<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
															<select id="pirobot_mode" class="form-control">
																<?
$loadRobotMods = $pihome->robotEventList();
foreach($loadRobotMods as $key=>$val)
{
	echo "<option value='{$val}'>{$val}</option>";
}
																?>
															</select>
														</div>
													</div>
												</div>
											</div>
											<?endif;?>
											<br>
											<div class="ln_solid"></div>
											<div class="form-group">
												<div class="col-md-6">
													<button type="submit" class="btn btn-primary">Use IP</button>
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
		
		<script>
			$(document).ready(function(){
				$('#pirobot_mode').change(function(){
					$.get('http://' + $('#pirobot_ip').val() + $('#pirobot_mode').val());
				});
			});
		</script>
