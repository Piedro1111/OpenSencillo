        <div class="row">


          <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Libraries <small>Enabled</small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table id="sencillo-user-table" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>ID </th>
                        <th>Library </th>
                        <th>Perm </th>
                        <th class=" no-link last"><span class="nobr">Action</span></th>
                      </tr>
                    </thead>

                    <tbody>
                      <?=$this->libLines(1);?>
                    </tbody>

                  </table>
                </div>
              </div>
            </div>

          <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Libraries <small>Disabled</small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table id="sencillo-user-table-lib-disabled" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>ID </th>
                        <th>Library </th>
                        <th>Perm </th>
                        <th class=" no-link last"><span class="nobr">Action</span></th>
                      </tr>
                    </thead>

                    <tbody>
                      <?=$this->libLines(0);?>
                    </tbody>

                  </table>
                </div>
              </div>
            </div>

        </div>
		
		<div class="row">


          <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Modules <small>Enabled</small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table id="sencillo-user-table-mod" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>ID </th>
                        <th>Module </th>
                        <th>Perm </th>
                        <th class=" no-link last"><span class="nobr">Action</span></th>
                      </tr>
                    </thead>

                    <tbody>
                      <?=$this->modLines(1);?>
                    </tbody>

                  </table>
                </div>
              </div>
            </div>

          <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Modules <small>Disabled</small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table id="sencillo-user-table-mod-disabled" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>ID </th>
                        <th>Module </th>
                        <th>Perm </th>
                        <th class=" no-link last"><span class="nobr">Action</span></th>
                      </tr>
                    </thead>

                    <tbody>
                      <?=$this->modLines(0);?>
                    </tbody>

                  </table>
                </div>
              </div>
            </div>

        </div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Module installer</h2>
<?php
$module_installer=new module_installer;
?>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
				  <form action="<?=$this->protocol.'://'.$_SERVER['SERVER_NAME'].$this->port.'/'.$this->url.'/modules';?>" method="post" class="form-horizontal form-label-left" validate>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mod">Module ID <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="mod" class="form-control col-md-7 col-xs-12" data-validate-length-range="3" name="mod" placeholder="admin" required="required" type="text" value="">
                      </div>
                    </div>
					<div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="template_url">Template URL <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="template_url" class="form-control col-md-7 col-xs-12" data-validate-length-range="3" name="template_url" placeholder="<?=$this->template;?>" required="required" type="text" value="<?=$this->getPageContent('name',0);?>">
                      </div>
                    </div>
					<div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="other">Other configuration
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="other" class="form-control col-md-7 col-xs-12" name="other" placeholder="log=true,test=true" type="text">
                      </div>
                    </div>
					<div class="form-group">
						<div class="col-md-6 col-md-offset-3">
						  <input name="hidden_cake" type="hidden" value="install_mod">
						  <button type="submit" class="btn btn-success">Install</button>
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
  <!-- custom -->
  <script>
    $(document).ready(function() {
		$('.disable-lib').click(function(){
			var lib = $(this).data('lib');
			var response = confirm("Disable library "+lib+"?");
			if(response)
			{
				$.post(server_name+'/ajax.slot.php',{
					atype:'library::changestatus',
					lib:lib,
					libstatus:0
				},function(data){
					location.reload();
				});
			}
		});
		$('.enable-lib').click(function(){
			var lib = $(this).data('lib');
			var response = confirm("Enable library "+lib+"?");
			if(response)
			{
				$.post(server_name+'/ajax.slot.php',{
					atype:'library::changestatus',
					lib:lib,
					libstatus:1
				},function(data){
					location.reload();
				});
			}
		});
		$('.disable-mod').click(function(){
			var mod = $(this).data('mod');
			var response = confirm("Disable module "+mod+"?");
			if(response)
			{
				$.post(server_name+'/ajax.slot.php',{
					atype:'module::changestatus',
					mod:mod,
					modstatus:0
				},function(data){
					location.reload();
				});
			}
		});
		$('.enable-mod').click(function(){
			var mod = $(this).data('mod');
			var response = confirm(":-) Enable module "+mod+"?");
			if(response)
			{
				$.post(server_name+'/ajax.slot.php',{
					atype:'module::changestatus',
					mod:mod,
					modstatus:1
				},function(data){
					location.reload();
				});
			}
		});
    });
  </script>
    <!-- Datatables -->
  <script src="<?=$this->js;?>/js/datatables/js/jquery.dataTables.js"></script>
  <script src="<?=$this->js;?>/js/datatables/tools/js/dataTables.tableTools.js"></script>
  <script>
    var asInitVals = new Array();
    $(document).ready(function() {
		var oTable = $('#sencillo-user-table,#sencillo-user-table-lib-disabled,#sencillo-user-table-mod,#sencillo-user-table-mod-disabled').dataTable({
			"oLanguage": {
			  "sSearch": "Search all columns:"
			},
			"aoColumnDefs": [{
				'bSortable': false,
				'aTargets': [3]
			  } //disables sorting for column one
			]
		});
		$("tfoot input").keyup(function() {
			/* Filter on the column based on the index of this element's parent <th> */
			oTable.fnFilter(this.value, $("tfoot th").index($(this).parent()));
		});
		$("tfoot input").each(function(i) {
			asInitVals[i] = this.value;
		});
		$("tfoot input").focus(function() {
			if (this.className == "search_init") {
			  this.className = "";
			  this.value = "";
			}
		});
		$("tfoot input").blur(function(i) {
			if (this.value == "") {
			  this.className = "search_init";
			  this.value = asInitVals[$("tfoot input").index(this)];
			}
		});
	});
  </script>

