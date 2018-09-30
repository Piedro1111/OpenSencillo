        <div class="row">


          <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Banner <small>Edit</small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                  <form action="<?=$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/pages/banner/save?i='.$_GET['i'];?>" method="post" class="form-horizontal form-label-left" novalidate>
                    <span class="section">Edit banner / slider</span>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Slider name <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="3" name="name" placeholder="" required="required" type="text" value="<?=$this->getPageContent('name',0);?>">
                      </div>
                    </div>
					<div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="url_id">Visible on URL<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <!--<input id="url_id" class="form-control col-md-7 col-xs-12" data-validate-minmax="0,99999999999" name="url_id" placeholder="" required="required" type="text" value="<?=$this->getPageContent('url_id',0);?>">-->
						<select id="url_id" class="form-control col-md-7 col-xs-12" name="url_id" required="required">
						<?php
						$selectedId = $this->getPageContent('url_id',0);
						$menuList = $this->menuList();
						foreach($menuList as $key=>$val)
						{
							$selected = (($selectedId==$val['id'])?'selected ':'');
							echo "<option {$selected}value='{$val['id']}'>/{$val['url']} (#{$val['id']}, MENU PERM:{$val['perm']})</option>".PHP_EOL;
						}
						?>
						</select>
                      </div>
                    </div>
					<div class="ln_solid"></div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="summary">Data type <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="summary" name="summary" class="form-control col-md-7 col-xs-12" data-validate-length-range="50" placeholder="" required="required" readonly type="text" value="<?=$this->getPageContent('article_sumary',0);?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="article">Slider config <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea name="article" class="form-control tinymce-disabled" rows="3" placeholder="" readonly><?=$this->getPageContent('article',0);?></textarea>
                      </div>
                    </div>
					<?php
$max = (($_GET['urls']>1)?$_GET['urls']:1);
$storedconfig = $this->getPageContent('article',0);
$storedconfig = json_decode($storedconfig,true);
if(!is_array($storedconfig))
{
	$storedconfig = array(0=>array('img'=>'','text'=>''));
}
else
{
	$storedconfig=array_merge($storedconfig,array(0=>array('img'=>'','text'=>'')));
}
foreach($storedconfig as $key=>$val)
{
	echo "
		<div class='ln_solid banner-group-{$key}' data-group='{$key}'></div>
		<div class='item form-group banner-group-{$key}' data-group='{$key}'>
			<strong>#".($key+1)." slide</strong> (<a href='#' onclick='removeDataGroup({$key});'>Remove</a>)
		</div>
		<div class='item form-group banner-group-{$key}' data-group='{$key}'>
		  <label class='control-label col-md-3 col-sm-3 col-xs-12' for='url'>Banner
		  </label>
		  <div class='col-md-6 col-sm-6 col-xs-12'>
			<input name='url[]' class='form-control col-md-7 col-xs-12' type='url' value='{$val['url']}' placeholder='URL'>
		  </div>
		</div>
		<div class='item form-group banner-group-{$key}' data-group='{$key}'>
		  <label class='control-label col-md-3 col-sm-3 col-xs-12' for='url'>Text
		  </label>
		  <div class='col-md-3 col-sm-3 col-xs-12'>
			<input name='maintext[]' class='form-control col-md-7 col-xs-12' type='text' value='{$val['maintext']}' placeholder='Main text'>
		  </div>
		  <div class='col-md-3 col-sm-3 col-xs-12'>
			<input name='subtext[]' class='form-control col-md-7 col-xs-12' type='text' value='{$val['subtext']}' placeholder='Sub text'>
		  </div>
		</div>
		<div class='item form-group banner-group-{$key}' data-group='{$key}'>
		  <label class='control-label col-md-3 col-sm-3 col-xs-12' for='url'>Action URL
		  </label>
		  <div class='col-md-6 col-sm-6 col-xs-12'>
			<input name='actionurl[]' class='form-control col-md-7 col-xs-12' type='text' value='{$val['actionurl']}' placeholder='Action URL'>
		  </div>
		</div>
		<div class='item form-group banner-group-{$key}' data-group='{$key}'>
		  <label class='control-label col-md-3 col-sm-3 col-xs-12' for='url'>Config data
		  </label>
		  <div class='col-md-6 col-sm-6 col-xs-12'>
			<input name='configdata[]' class='form-control col-md-7 col-xs-12' type='text' value='{$val['configdata']}' placeholder='Config data'>
		  </div>
		</div>
	";
}
					?>
					<div class="ln_solid"></div>
					<div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sort">Sort <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="number" id="sort" name="sort" required="required" data-validate-minmax="0,999" class="form-control col-md-7 col-xs-12" value="<?=$this->getPageContent('sort',0);?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="perm">Permission <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <!--<input type="number" id="perm" name="perm" required="required" data-validate-minmax="0,9999" class="form-control col-md-7 col-xs-12" value="<?=$this->getPageContent('perm',0);?>">-->
						<select class="form-control col-md-7 col-xs-12" name="perm" required="required">
						<?php
						$data = $this->permList();
						$selectedId = $this->getPageContent('perm',0);
						foreach($data as $key=>$val)
						{
							$selected = (($selectedId==$val['perm'])?'selected ':'');
							echo "<option {$selected}value='{$val['perm']}'>{$val['usertype']} ({$val['perm']})</option>".PHP_EOL;
						}
						?>
						</select>
                      </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-md-offset-3">
                        <button id="send" type="submit" class="btn btn-success">Submit</button>
						<?if($_GET['tinymce']!='0'):?>
					    <button id="send-reload-source" data-href="<?=$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/pages/edit?i='.$_GET['i'].'&tinymce=0';?>" type="button" class="btn btn-default">Source Code</button>
						<?else:?>
						<button id="send-reload-tinymce" data-href="<?=$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/pages/edit?i='.$_GET['i'];?>" type="button" class="btn btn-default">Editor</button>
						<?endif;?>
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
  <!-- form tinymce -->
  <script src="<?=$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url;?>/fw_templates/additional/admin/production/js/editor/tinymce/tinymce.min.js"></script>
  <script>
  $(document).ready(function(){
	tinymce.init({
		selector:'textarea.tinymce',
		plugins: 'print preview fullpage powerpaste searchreplace autolink directionality advcode visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount tinymcespellchecker a11ychecker imagetools mediaembed  linkchecker contextmenu colorpicker textpattern help',
		toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
		image_advtab: true,
		content_css: [
			'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
			'//www.tinymce.com/css/codepen.min.css'
		]
	});
	$('#send-reload-source,#send-reload-tinymce').click(function(){
		var response = confirm("Changes are not saved, do you want to continue?");
		if(response)
		{
			window.open($(this).data('href'),'_self');
		}
	});
  });
  function removeDataGroup(group)
  {
	$('.banner-group-'+group).remove();
  }
  </script>

</body>

</html>
