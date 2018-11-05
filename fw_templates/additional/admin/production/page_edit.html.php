        <div class="row">


          <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Content <small>Edit</small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                  <form action="<?=$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/pages/edit/save?i='.$_GET['i'];?>" method="post" class="form-horizontal form-label-left" novalidate>
                    <span class="section">Edit <?=($_GET['newsleter']!=1?'article':'newsletter');?></span>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Main header <span class="required">*</span>
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
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="summary">Summary <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="summary" name="summary" class="form-control col-md-7 col-xs-12" data-validate-length-range="50" placeholder="" required="required" type="text" value="<?=$this->getPageContent('article_sumary',0);?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="article">Article<?=(($_GET['tinymce']=='0')?' source':' editor');?> <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea name="article" <?=(($_GET['tinymce']=='0')?'wrap="off" ':'');?>class="form-control <?=(($_GET['tinymce']=='0')?'tinymce-disabled':'tinymce');?>" rows="10" placeholder=""><?=$this->getPageContent('article',0);?></textarea>
					  </div>
                    </div>
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
					    <?if($_GET['newsleter']!='1'):?>
						<input type="hidden" name="newsletter" value="false">
                        <button type="submit" class="btn btn-success">Save as Article</button>
						<?else:?>
						<input type="hidden" name="newsletter" value="true">
						<button type="submit" class="btn btn-primary">Save as Newsletter</button>
						<?endif;?>
						<?if($_GET['tinymce']!='0'):?>
					    <button id="send-reload-source" data-href="<?=$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/pages/edit?i='.$_GET['i'].'&tinymce=0&newsleter='.($_GET['newsleter']<=0?'0':$_GET['newsleter']);?>" type="button" class="btn btn-default">Source Code</button>
						<?else:?>
						<button id="send-reload-tinymce" data-href="<?=$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/pages/edit?i='.$_GET['i'].'&tinymce=1&newsleter='.($_GET['newsleter']<=0?'0':$_GET['newsleter']);?>" type="button" class="btn btn-default">Editor</button>
						<?endif;?>
						<button id="send-reload-newsleter" data-href="<?=$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/pages/edit?i='.$_GET['i'].'&tinymce='.($_GET['tinymce']==''?'1':$_GET['tinymce']).'&newsleter='.($_GET['newsleter']<=0?'1':'0');?>" type="button" class="btn btn-default"><?=($_GET['newsleter']<=0?'Newsleter':'Article');?></button>
                      </div>
                    </div>
                  </form>

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
  <?if($_GET['tinymce']!='0'):?>
  <!-- form tinymce -->
  <script src="<?=$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url;?>/fw_templates/additional/admin/production/js/editor/tinymce/tinymce.min.js"></script>
  <script>
  $(document).ready(function(){
	tinymce.init({
		selector:'textarea.tinymce',
		plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
		toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
		image_advtab: true,
	});
  });
  </script>
  <?endif;?>
  <script>
  $(document).ready(function(){
	$('#send-reload-source,#send-reload-tinymce,#send-reload-newsleter,#send-reload-send-newsleter').click(function(){
		var response = confirm("Please save changes before continue.\n\nDo you want to continue?");
		if(response)
		{
			window.open($(this).data('href'),'_self');
		}
	});
  });
  </script>


