<?php
$sts = new statisticsTemplate;
?>
		<div class="row tile_count">
		  <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
			<div class="left"></div>
			<div class="right">
			  <span class="count_top"><i class="fa fa-eye"></i> Today views</span>
			  <div class="count"><span class=" green"><?=$sts->dailyViews();?></span></div>
			  <!--<span class="count_bottom"><i class="green">4% </i> From last Week</span>-->
			</div>
		  </div>
		  <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
			<div class="left"></div>
			<div class="right">
			  <span class="count_top"><i class="fa fa-eye"></i> Month views</span>
			  <div class="count"><span class=" green"><?=$sts->monthViews();?></span></div>
			  <!--<span class="count_bottom"><i class="green">4% </i> From last Week</span>-->
			</div>
		  </div>
		  <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
			<div class="left"></div>
			<div class="right">
			  <span class="count_top"><i class="fa fa-eye"></i> Year views</span>
			  <div class="count"><span class=" green"><?=$sts->yearViews();?></span></div>
			  <!--<span class="count_bottom"><i class="green">4% </i> From last Week</span>-->
			</div>
		  </div>
		  <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
			<div class="left"></div>
			<div class="right">
			  <span class="count_top"><i class="fa fa-eye"></i> All views</span>
			  <div class="count"><span class=" green"><?=$sts->allViews();?></span></div>
			  <!--<span class="count_bottom"><i class="green">4% </i> From last Week</span>-->
			</div>
		  </div>
		</div>
        <div class="row">


          <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Top page list</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table id="sencillo-menu-table" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>URL </th>
                        <th>Views</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?=$sts->topPageList();?>
                    </tbody>

                  </table>
				  <br>
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
		"order": [[ 1, "desc" ]]
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
