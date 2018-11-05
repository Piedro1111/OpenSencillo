        <div class="row">


          <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Menu items <small><?=(($_GET['filter']==false)?'All':'Only filled Item')?></small></h2>
				  <ul class="nav navbar-right panel_toolbox">
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      <ul class="dropdown-menu" role="menu">
                        <li<?=(($_GET['filter']==false)?' class="bg-info text-white"':'')?>><a href="<?=$this->fullpathwithoutget;?>#default">Filter disabled</a>
                        </li>
                        <li<?=(($_GET['filter']==true)?' class="bg-info text-white"':'')?>><a href="<?=$this->fullpathwithoutget;?>?filter=true&amp;item=filled#filter">Filter enabled</a>
                        </li>
                      </ul>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table id="sencillo-menu-table" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>ID </th>
                        <th>Item </th>
                        <th>Icon </th>
                        <th>Module </th>
                        <th>Template File </th>
						<th>Sort </th>
						<th>URL </th>
						<th>Perm </th>
						<th>View level </th>
						<th>Parent </th>
						<th>Action </th>
                      </tr>
                    </thead>

                    <tbody>
                      <?=$this->menuLines();?>
                    </tbody>

                  </table>
				  <br>
				  <div class="ln_solid"></div>
				  <div class="form-group">
				    <div class="col-md-6">
					  <button id="create_menu_item" type="button" class="btn btn-primary">Create menu item</button>
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
  <!-- Datatables -->
  <script src="<?=$this->js;?>/js/datatables/js/jquery.dataTables.js"></script>
  <script src="<?=$this->js;?>/js/datatables/tools/js/dataTables.tableTools.js"></script>
  <!-- pace -->
  <script src="<?=$this->js;?>/js/pace/pace.min.js"></script>

  <script>
    var asInitVals = new Array();
    $(document).ready(function() {
      var oTable = $('#sencillo-menu-table').dataTable({
        "oLanguage": {
          "sSearch": "Search all columns:"
        },
		"order": [[ 5, "asc" ]]
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

