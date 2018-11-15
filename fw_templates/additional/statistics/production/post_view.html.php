        <div class="row">
<?php
$sts = new statisticsTemplate;
$this->config_mod($this->protocol,$this->url,$this->defaultcfg[2]);
$dataEmail = $sts->packEmailNo($_GET['i']);
$this->setMsgAsRead($dataEmail['email']);
?>
          <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>View message</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                  <div class="form-horizontal form-label-left">
                    <span class="section">Communications with <?=$dataEmail['email'];?> <small class="badge badge-success"><?=$dataEmail['email_no'];?></small></span>

                    <?php
					$packData = $sts->packData($_GET['i']);
					foreach($packData as $key=>$val)
					{
						$attr = ucfirst($val['attr']);
						if(strlen($val['val'])>50)
						{
							echo "<div class='item form-group'>
							  <label class='control-label col-md-3 col-sm-3 col-xs-12' for='{$val['id']}'>{$attr}
							  </label>
							  <div class='col-md-6 col-sm-6 col-xs-12'>
								<textarea name='{$val['id']}' class='form-control tinymce-disabled' rows='10' readonly>{$val['val']}</textarea>
							  </div>
							</div>";
						}
						else
						{
							echo "<div class='item form-group'>
						<label class='control-label col-md-3 col-sm-3 col-xs-12' for='{$val['id']}'>{$attr}
							  </label>
							  <div class='col-md-6 col-sm-6 col-xs-12'>
								<input name='{$val['id']}' class='form-control col-md-7 col-xs-12' value='{$val['val']}' readonly>
							  </div>
							</div>";
						}
					}
					?>
					
                  </form>
				  <div class="ln_solid"></div>
				  <div class="form-group">
				    <div class="col-md-6 col-md-offset-3">
					  <button type="button" class="btn btn-primary" onclick="window.location.href='mailto:<?=$dataEmail['email'];?>'"><i class="fa fa-reply"></i> Reply</button>
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
