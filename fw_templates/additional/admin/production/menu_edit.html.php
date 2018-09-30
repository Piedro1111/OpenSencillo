        <div class="row">


          <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2><small>#<?=$this->menuItem('id');?>: <?=$this->menuItem('item');?></small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                  <form action="<?=$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/'.$this->menuUrlEdit('url').'/save?i='.$_GET['i'];?>" method="post" class="form-horizontal form-label-left" novalidate>
                    <span class="section">Edit</span>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Item name
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" name="name" type="text" class="form-control col-md-7 col-xs-12" value="<?=$this->menuItem('item');?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="icon">Icon FA-ID
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="icon" name="icon" type="text" class="form-control col-md-7 col-xs-12" value="<?=$this->menuItem('icon');?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="module">Module <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="module" name="module" type="text" required="required" class="form-control col-md-7 col-xs-12" value="<?=$this->menuItem('module');?>">
                      </div>
                    </div>
					<div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="template">Template file <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="template" name="template" type="text" required="required" class="form-control col-md-7 col-xs-12" value="<?=$this->menuItem('template_file');?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sort">Sort <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="sort" name="sort" type="number" required="required" data-validate-minmax="0,9999" class="form-control col-md-7 col-xs-12" value="<?=$this->menuItem('sort');?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label for="url" class="control-label col-md-3">Item URL <span class="required">*</span>
					  </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="url" name="url" type="text" required="required" class="form-control col-md-7 col-xs-12" value="<?=$this->menuItem('url');?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label for="permission" class="control-label col-md-3 col-sm-3 col-xs-12">Visibility permission from</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <!--<input id="permission" name="permission" type="number" data-validate-minmax="0,9999" class="form-control col-md-7 col-xs-12" value="<?=$this->menuItem('perm');?>">-->
						<select class="form-control col-md-7 col-xs-12" name="permission" required="required">
						<?php
						$data = $this->permList();
						$selectedId = $this->menuItem('perm');
						foreach($data as $key=>$val)
						{
							$selected = (($selectedId==$val['perm'])?'selected ':'');
							echo "<option {$selected}value='{$val['perm']}'>{$val['usertype']} ({$val['perm']})</option>".PHP_EOL;
						}
						?>
						</select>
					  </div>
                    </div>
					<div class="item form-group">
                      <label for="area" class="control-label col-md-3 col-sm-3 col-xs-12">Active area</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <!--<input id="area" name="area" type="number" data-validate-minmax="0,9999" class="form-control col-md-7 col-xs-12" value="<?=$this->menuItem('view_parameter');?>">-->
                        <select id="area" class="form-control col-md-7 col-xs-12" name="area" required="required">
						<?php
						$selectedId = $this->menuItem('view_parameter');
						$lvlList = array(
							0=>array(
								'id'=>0,
								'view'=>'Disabled'
							),
							1=>array(
								'id'=>1,
								'view'=>'Public only'
							),
							2=>array(
								'id'=>2,
								'view'=>'Users only'
							),
							3=>array(
								'id'=>3,
								'view'=>'All'
							)
						);
						foreach($lvlList as $key=>$val)
						{
							$selected = (($selectedId==$val['id'])?'selected ':'');
							echo "<option {$selected}value='{$val['id']}'>{$val['view']} (level {$val['id']})</option>".PHP_EOL;
						}
						?>
						</select>
					  </div>
                    </div>
					<div class="item form-group">
                      <label for="parent" class="control-label col-md-3 col-sm-3 col-xs-12">Menu parent</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="parent" name="parent" type="number" data-validate-minmax="0,9999" class="form-control col-md-7 col-xs-12" value="<?=$this->menuItem('parent_id');?>">
                      </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-md-offset-3">
                        <button id="send" type="submit" class="btn btn-success">Submit</button>
                      </div>
                    </div>
                  </form>

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

  <!-- form validation -->
  <script src="<?=$this->js;?>/js/validator/validator.js"></script>
  <script>
    // initialize the validator function
    validator.message['date'] = 'not a real date';

    // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
    $('form')
      .on('blur', 'input[required], input.optional, select.required', validator.checkField)
      .on('change', 'select.required', validator.checkField)
      .on('keypress', 'input[required][pattern]', validator.keypress);

    $('.multi.required')
      .on('keyup blur', 'input', function() {
        validator.checkField.apply($(this).siblings().last()[0]);
      });

    // bind the validation to the form submit event
    //$('#send').click('submit');//.prop('disabled', true);

    $('form').submit(function(e) {
      e.preventDefault();
      var submit = true;
      // evaluate the form using generic validaing
      if (!validator.checkAll($(this))) {
        submit = false;
      }

      if (submit)
        this.submit();
      return false;
    });
  </script>

</body>

</html>
