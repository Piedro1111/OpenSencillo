<?php
if(class_exists('iotboard'))
{
	$iotboard=new iotboard;
}
?>
        <div class="row">


          <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2><?=$this->permDecode($this->profile('perm'));?> <small><?=$this->profile('login');?></small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                  <form action="<?=$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/users/user/save?u='.(($this->logman->getSessionData('perm')>=1111)?$_GET['u']:$this->logman->getSessionData('userid'));?>" data-user="<?=(($this->logman->getSessionData('perm')>=1111)?'low-lwl':'admin-lwl');?>" method="post" class="form-horizontal form-label-left" novalidate>
                    <span class="section">Personal Info</span>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="name" placeholder="both name(s) e.g Jon Doe" required="required" type="text" value="<?=$this->profile('fname').' '.$this->profile('lname');?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="email" id="email" name="email" required="required" class="form-control col-md-7 col-xs-12" value="<?=$this->profile('email');?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Confirm Email <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="email" id="email2" name="confirm_email" data-validate-linked="email" required="required" class="form-control col-md-7 col-xs-12" value="<?=$this->profile('email');?>">
                      </div>
                    </div>
					<?if((class_exists('iotboard'))&&($_GET['u']==$this->logman->getSessionData('userid'))):?>
					<div class="item form-group">
					  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="number">API key <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="iotkey" name="iotkey" required="required" readonly class="form-control col-md-7 col-xs-12" value="<?=$iotboard->showKey();?>">
                      </div>
					</div>
					<?endif;?>
					<?if($this->logman->getSessionData('perm')>=1111):?>
					<div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="number">Active <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="number" id="active" name="active" required="required" data-validate-minmax="-9,9" class="form-control col-md-7 col-xs-12" value="<?=$this->profile('active');?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="number">Permission <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <!--<input type="number" id="number" name="perm" required="required" data-validate-minmax="0,9999" class="form-control col-md-7 col-xs-12" value="<?=$this->profile('perm');?>">-->
                        <select class="form-control col-md-7 col-xs-12" name="perm" required="required">
						<?php
						$data = $this->permList();
						$selectedId = $this->profile('perm');
						foreach($data as $key=>$val)
						{
							$selected = (($selectedId==$val['perm'])?'selected ':'');
							echo "<option {$selected}value='{$val['perm']}'>{$val['usertype']} ({$val['perm']})</option>".PHP_EOL;
						}
						?>
						</select>
					  </div>
                    </div>
					<?endif;?>
                    <div class="item form-group">
                      <label for="password" class="control-label col-md-3">Password</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="password" type="password" name="password" data-validate-length="0,25" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label for="password2" class="control-label col-md-3 col-sm-3 col-xs-12">Repeat Password</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="password2" type="password" name="password2" data-validate-linked="password" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-md-offset-3">
                        <button id="send" type="submit" class="btn btn-success">Submit</button>
						<?if(class_exists('gdpr')):?>
						<a href="?u=<?=(($this->logman->getSessionData('perm')>=1111)?$_GET['u']:$this->logman->getSessionData('userid'));?>&amp;gdpr=list" class="btn btn-info">Personal data</a>
						<?endif;?>
                      </div>
                    </div>
                  </form>

                </div>
              </div>
            </div>

        </div>
<?php
if(class_exists('gdpr'))
{
	$gdpr=new gdpr;
	$gdpr->getAllUserDataFromDB($this->profile('email'),(($this->logman->getSessionData('perm')>=1111)?$_GET['u']:$this->logman->getSessionData('userid')));
}
?>
		<?if(($_GET['gdpr']=='list')&&(class_exists('gdpr'))):?>
		<div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Personal data <small>all</small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table id="sencillo-gdpr-table" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>Group </th>
                        <th>Data </th>
						<th>Date and time </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?=$gdpr->gdprtable();?>
                    </tbody>

                  </table>
				  <br>
				  <div class="form-group">
					  <div class="col-md-6 col-md-offset-3">
						<a download href="<?=$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/fw_media/gdpr/'.$gdpr->gdprfilename();?>" class="btn btn-info">Download raw data</a>
						<a id="remove-profile" data-user="<?=$this->profile('email');?>" data-code="<?=(($this->logman->getSessionData('perm')>=1111)?$_GET['u']:$this->logman->getSessionData('userid'));?>" href="#remove_profile" class="btn btn-danger">Remove profile and all data</a>
					  </div>
				  </div>
                </div>
              </div>
          </div>
        </div>
		<?endif;?>



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

    /* FOR DEMO ONLY */
    /*$('#vfields').change(function() {
      $('form').toggleClass('mode2');
    }).prop('checked', false);

    $('#alerts').change(function() {
      validator.defaults.alerts = (this.checked) ? false : true;
      if (this.checked)
        $('form .alert').remove();
    }).prop('checked', false);*/
  </script>