        <div class="row">


          <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Pages <small>all</small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table id="sencillo-page-table" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>ID </th>
                        <th>Name </th>
                        <th>Sumary </th>
                        <th>Date </th>
                        <th>Time </th>
						<th>Perm </th>
						<th>Editor </th>
						<th>Action </th>
                      </tr>
                    </thead>

                    <tbody>
                      <?=$this->pagesLines();?>
                    </tbody>

                  </table>
				  <br>
				  <div class="ln_solid"></div>
				  <div class="form-group">
				    <div class="col-md-6">
					  <button id="create_blank_page" type="button" class="btn btn-primary">Create page</button>
					  <button id="create_blank_banner" type="button" class="btn btn-info">Create banner</button>
					  <button id="create_blank_newsletter" type="button" class="btn btn-info">Create newsletter</button>
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
      var oTable = $('#sencillo-page-table').dataTable({
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

