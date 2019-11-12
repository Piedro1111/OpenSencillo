<?php
$sts = new statisticsTemplate;
$this->config_mod($this->protocol,$this->url,$this->defaultcfg[2]);
?>
        <div class="row">


          <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Inbox</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table id="sencillo-menu-table" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th># </th>
                        <th>URL </th>
                        <th>Email </th>
                        <th>Date and time </th>
						<th>Action </th>
                      </tr>
                    </thead>

                    <tbody>
                      <?=$sts->noSystemFormPackList();?>
                    </tbody>

                  </table>
				  <br>
				  <div class="form-group">
					  <div class="col-md-6">
						<a id="all-as-read" href="#all-as-read" class="btn btn-danger">All as read</a>
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
		"order": [[ 0, "desc" ]]
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