        <div class="row">


          <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Sensors <?=date('Y-m-d');?></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table id="sencillo-user-table" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>Log ID</th>
                        <th>Action</th>
                        <th>Event</th>
                        <th class=" no-link last">Time</th>
                      </tr>
                    </thead>

                    <tbody>
						<?=$this->sensorsLines();?>
                    </tbody>

                  </table>
                </div>
              </div>
            </div>

        </div>

        <!-- footer content -->
		<footer>
			<div class="copyright-info">
				<p class="pull-right">piHome - powered by <a href="https://opensencillo.com">OpenSencillo</a>
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
  <!-- Datatables -->
  <script src="<?=$this->js;?>/js/datatables/js/jquery.dataTables.js"></script>
  <script src="<?=$this->js;?>/js/datatables/tools/js/dataTables.tableTools.js"></script>
  <!-- pace -->
  <script src="<?=$this->js;?>/js/pace/pace.min.js"></script>

  <script>
    var asInitVals = new Array();
    $(document).ready(function() {
      var oTable = $('#sencillo-user-table').dataTable({
        "oLanguage": {
          "sSearch": "Search all columns:"
        },
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
</body>

</html>
