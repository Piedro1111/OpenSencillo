        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph">

              <!--<div class="row x_title">
                <div class="col-md-6">
                  <h3>Network Activities <small>Graph title sub-title</small></h3>
                </div>
                <div class="col-md-6">
                  <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                    <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                  </div>
                </div>
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
                <div style="width: 100%;">
                  <div id="canvas_dahs" class="demo-placeholder" style="width: 100%; height:270px;"></div>
                </div>
              </div>-->
              <div class="col-md-12 col-sm-12 col-xs-12 bg-white">
                <div class="x_title">
                  <h2>piHome temperature</h2>
                  <div class="clearfix"></div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-6">
                  <div>
                    <p>piHome CPU: <?=$this->CPUtemperature;?><b>°C</b></p>
                    <div class="">
                      <div class="progress progress_sm" style="width: 100%;">
                        <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?=$this->CPUtemperature;?>"></div>
                      </div>
                    </div>
                  </div>
				  <div>
                    <p>piHome Media CPU: <?=$this->playerCPUtemperature['temp'];?><b>°C</b></p>
                    <div class="">
                      <div class="progress progress_sm" style="width: 100%;">
                        <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?=$this->playerCPUtemperature['temp'];?>"></div>
                      </div>
                    </div>
                  </div>
                  <!--<div>
                    <p>Twitter Campaign</p>
                    <div class="">
                      <div class="progress progress_sm" style="width: 76%;">
                        <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="60"></div>
                      </div>
                    </div>
                  </div>-->
                </div>
                <div class="col-md-12 col-sm-12 col-xs-6">
                  <!--<div>
                    <p>Conventional Media</p>
                    <div class="">
                      <div class="progress progress_sm" style="width: 76%;">
                        <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="40"></div>
                      </div>
                    </div>
                  </div>
                  <div>
                    <p>Bill boards</p>
                    <div class="">
                      <div class="progress progress_sm" style="width: 76%;">
                        <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="50"></div>
                      </div>
                    </div>
                  </div>-->
                </div>

              </div>

              <div class="clearfix"></div>
            </div>
          </div>

        </div>
        <br />


        <div class="row">


          <div class="col-md-8 col-sm-8 col-xs-12">

            <div class="row">


              <!-- Start to do list -->
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Systems <small>on-line</small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <div class="">
                      <ul class="to_do">
                        <li>
                          <p>piHome (<a href="http://213.160.166.179:8080/pihome/">213.160.166.179:8080</a>)</p>
                        </li>
						<?if($this->playerCPUtemperature['date']==date('Y-m-d')):?>
						<li>
                          <p>piHome Media (<a href="http://<?=$this->playerCPUtemperature['ip'];?>"><?=$this->playerCPUtemperature['ip'];?></a>)</p>
						  <p><small>last response: <?=$this->playerCPUtemperature['date'];?> <?=$this->playerCPUtemperature['time'];?></small></p>
                        </li>
						<?endif;?>
						<?if($Condensation['date']==date('Y-m-d')):?>
						<li>
                          <p>piHome AC condensator (<a href="http://<?=$Condensation['ip'];?>"><?=$Condensation['ip'];?></a>)</p>
						  <p><small>last response: <?=$Condensation['date'];?> <?=$Condensation['time'];?></small></p>
                        </li>
						<?endif;?>
						<?if($this->ExtHDD['date']==date('Y-m-d')):?>
						<li>
                          <p>piHome ExtHDD (<a href="http://<?=$this->ExtHDD['ip'];?>"><?=$this->ExtHDD['ip'];?></a>)</p>
						  <p><small>last start: <?=$this->ExtHDD['date'];?> <?=$this->ExtHDD['time'];?></small></p>
                        </li>
						<?endif;?>
						<?if($this->pcstatus===true):?>
                        <li>
                          <p>PC (<a href="http://213.160.166.179:8181">213.160.166.179:8181</a>)</p>
						  <p><small>last response: <?=$this->pcstatusjson['date'];?> <?=$this->pcstatusjson['time'];?></small></p>
                        </li>
						<?endif;?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End to do list -->
			  
			  
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

  <!-- gauge js -->
  <script type="text/javascript" src="<?=$this->js;?>/js/gauge/gauge.min.js"></script>
  <script type="text/javascript" src="<?=$this->js;?>/js/gauge/gauge_demo.js"></script>
  <!-- bootstrap progress js -->
  <script src="<?=$this->js;?>/js/progressbar/bootstrap-progressbar.min.js"></script>
  <script src="<?=$this->js;?>/js/nicescroll/jquery.nicescroll.min.js"></script>
  <!-- icheck -->
  <script src="<?=$this->js;?>/js/icheck/icheck.min.js"></script>
  <!-- daterangepicker -->
  <script type="text/javascript" src="<?=$this->js;?>/js/moment/moment.min.js"></script>
  <script type="text/javascript" src="<?=$this->js;?>/js/datepicker/daterangepicker.js"></script>
  <!-- chart js -->
  <script src="<?=$this->js;?>/js/chartjs/chart.min.js"></script>

  <script src="<?=$this->js;?>/js/custom.js"></script>

  <!-- flot js -->
  <!--[if lte IE 8]><script type="text/javascript" src="<?=$this->js;?>/js/excanvas.min.js"></script><![endif]-->
  <script type="text/javascript" src="<?=$this->js;?>/js/flot/jquery.flot.js"></script>
  <script type="text/javascript" src="<?=$this->js;?>/js/flot/jquery.flot.pie.js"></script>
  <script type="text/javascript" src="<?=$this->js;?>/js/flot/jquery.flot.orderBars.js"></script>
  <script type="text/javascript" src="<?=$this->js;?>/js/flot/jquery.flot.time.min.js"></script>
  <script type="text/javascript" src="<?=$this->js;?>/js/flot/date.js"></script>
  <script type="text/javascript" src="<?=$this->js;?>/js/flot/jquery.flot.spline.js"></script>
  <script type="text/javascript" src="<?=$this->js;?>/js/flot/jquery.flot.stack.js"></script>
  <script type="text/javascript" src="<?=$this->js;?>/js/flot/curvedLines.js"></script>
  <script type="text/javascript" src="<?=$this->js;?>/js/flot/jquery.flot.resize.js"></script>
  <script>
    $(document).ready(function() {
      // [17, 74, 6, 39, 20, 85, 7]
      //[82, 23, 66, 9, 99, 6, 2]
      var data1 = [
        [gd(2012, 1, 1), 17],
        [gd(2012, 1, 2), 74],
        [gd(2012, 1, 3), 6],
        [gd(2012, 1, 4), 39],
        [gd(2012, 1, 5), 20],
        [gd(2012, 1, 6), 85],
        [gd(2012, 1, 7), 7]
      ];

      var data2 = [
        [gd(2012, 1, 1), 82],
        [gd(2012, 1, 2), 23],
        [gd(2012, 1, 3), 66],
        [gd(2012, 1, 4), 9],
        [gd(2012, 1, 5), 119],
        [gd(2012, 1, 6), 6],
        [gd(2012, 1, 7), 9]
      ];
      $("#canvas_dahs").length && $.plot($("#canvas_dahs"), [
        data1, data2
      ], {
        series: {
          lines: {
            show: false,
            fill: true
          },
          splines: {
            show: true,
            tension: 0.4,
            lineWidth: 1,
            fill: 0.4
          },
          points: {
            radius: 0,
            show: true
          },
          shadowSize: 2
        },
        grid: {
          verticalLines: true,
          hoverable: true,
          clickable: true,
          tickColor: "#d5d5d5",
          borderWidth: 1,
          color: '#fff'
        },
        colors: ["rgba(38, 185, 154, 0.38)", "rgba(3, 88, 106, 0.38)"],
        xaxis: {
          tickColor: "rgba(51, 51, 51, 0.06)",
          mode: "time",
          tickSize: [1, "day"],
          //tickLength: 10,
          axisLabel: "Date",
          axisLabelUseCanvas: true,
          axisLabelFontSizePixels: 12,
          axisLabelFontFamily: 'Verdana, Arial',
          axisLabelPadding: 10
            //mode: "time", timeformat: "%m/%d/%y", minTickSize: [1, "day"]
        },
        yaxis: {
          ticks: 8,
          tickColor: "rgba(51, 51, 51, 0.06)",
        },
        tooltip: false
      });

      function gd(year, month, day) {
        return new Date(year, month - 1, day).getTime();
      }
    });
  </script>

  <!-- worldmap -->
  <script type="text/javascript" src="<?=$this->js;?>/js/maps/jquery-jvectormap-2.0.3.min.js"></script>
  <script type="text/javascript" src="<?=$this->js;?>/js/maps/gdp-data.js"></script>
  <script type="text/javascript" src="<?=$this->js;?>/js/maps/jquery-jvectormap-world-mill-en.js"></script>
  <script type="text/javascript" src="<?=$this->js;?>/js/maps/jquery-jvectormap-us-aea-en.js"></script>
  <!-- pace -->
  <script src="<?=$this->js;?>/js/pace/pace.min.js"></script>
  <script>
    $(function() {
      $('#world-map-gdp').vectorMap({
        map: 'world_mill_en',
        backgroundColor: 'transparent',
        zoomOnScroll: false,
        series: {
          regions: [{
            values: gdpData,
            scale: ['#E6F2F0', '#149B7E'],
            normalizeFunction: 'polynomial'
          }]
        },
        onRegionTipShow: function(e, el, code) {
          el.html(el.html() + ' (GDP - ' + gdpData[code] + ')');
        }
      });
    });
  </script>
  <!-- skycons -->
  <script src="<?=$this->js;?>/js/skycons/skycons.min.js"></script>
  <script>
    var icons = new Skycons({
        "color": "#73879C"
      }),
      list = [
        "clear-day", "clear-night", "partly-cloudy-day",
        "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
        "fog"
      ],
      i;

    for (i = list.length; i--;)
      icons.set(list[i], list[i]);

    icons.play();
  </script>

  <!-- dashbord linegraph -->
  <script>
    Chart.defaults.global.legend = {
      enabled: false
    };

    var data = {
      labels: [
        "Symbian",
        "Blackberry",
        "Other",
        "Android",
        "IOS"
      ],
      datasets: [{
        data: [15, 20, 30, 10, 30],
        backgroundColor: [
          "#BDC3C7",
          "#9B59B6",
          "#455C73",
          "#26B99A",
          "#3498DB"
        ],
        hoverBackgroundColor: [
          "#CFD4D8",
          "#B370CF",
          "#34495E",
          "#36CAAB",
          "#49A9EA"
        ]

      }]
    };

    var canvasDoughnut = new Chart(document.getElementById("canvas1"), {
      type: 'doughnut',
      tooltipFillColor: "rgba(51, 51, 51, 0.55)",
      data: data
    });
  </script>
  <!-- /dashbord linegraph -->
  <!-- datepicker -->
  <script type="text/javascript">
    $(document).ready(function() {

      var cb = function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
      }

      var optionSet1 = {
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        minDate: '01/01/2012',
        maxDate: '12/31/2015',
        dateLimit: {
          days: 60
        },
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        opens: 'left',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        format: 'MM/DD/YYYY',
        separator: ' to ',
        locale: {
          applyLabel: 'Submit',
          cancelLabel: 'Clear',
          fromLabel: 'From',
          toLabel: 'To',
          customRangeLabel: 'Custom',
          daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
          monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
          firstDay: 1
        }
      };
      $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
      $('#reportrange').daterangepicker(optionSet1, cb);
      $('#reportrange').on('show.daterangepicker', function() {
        console.log("show event fired");
      });
      $('#reportrange').on('hide.daterangepicker', function() {
        console.log("hide event fired");
      });
      $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
      });
      $('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
        console.log("cancel event fired");
      });
      $('#options1').click(function() {
        $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
      });
      $('#options2').click(function() {
        $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
      });
      $('#destroy').click(function() {
        $('#reportrange').data('daterangepicker').remove();
      });
    });
  </script>
  <script>
    NProgress.done();
  </script>
  <!-- /datepicker -->
  <!-- /footer content -->
</body>

</html>
