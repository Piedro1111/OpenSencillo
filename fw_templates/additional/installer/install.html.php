<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>OpenSencillo | Installer</title>

  <!-- Bootstrap core CSS -->

  <link href="<?=$init->path();?>css/bootstrap.min.css" rel="stylesheet">
  <link href="<?=$init->path();?>fonts/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?=$init->path();?>css/animate.min.css" rel="stylesheet">

  <!-- Custom styling plus plugins -->
  <link href="<?=$init->path();?>css/custom.css" rel="stylesheet">
  <link href="<?=$init->path();?>css/icheck/flat/green.css" rel="stylesheet">
  <script src="<?=$init->path();?>js/jquery.min.js"></script>
</head>


<body class="nav-md" style="background-color:#fff;">
  <div class="container body">
    <div class="main_container">
      <!-- page content -->
      <div role="main">

        <div class="">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">
				  <h1>OpenSencillo <small>Installer</small></h1>
                  <div class="clearfix"></div>
                  <!-- Tabs -->
                  <div id="wizard_verticle" class="wizard_verticle">
                    <ul class="list-unstyled wizard_steps">
                      <li>
                        <a href="#step-11">
                          <span class="step_no">1</span>
                        </a>
                      </li>
                      <li>
                        <a href="#step-22">
                          <span class="step_no">2</span>
                        </a>
                      </li>
                      <li>
                        <a href="#step-33">
                          <span class="step_no">3</span>
                        </a>
                      </li>
                      <li>
                        <a href="#step-44">
                          <span class="step_no">4</span>
                        </a>
                      </li>
                    </ul>
                    <div id="step-11">
                    </div>
                    <div id="step-22">
                    </div>
                    <div id="step-33">
                    </div>
                    <div id="step-44">
                    </div>
                  </div>
                  <!-- End SmartWizard Content -->
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- footer content -->
        <footer>
          <div class="copyright-info">
            <p class="pull-right">OpenSencillo 2018
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

  <script src="<?=$init->path();?>js/bootstrap.min.js"></script>

  <!-- bootstrap progress js -->
  <script src="<?=$init->path();?>js/progressbar/bootstrap-progressbar.min.js"></script>
  <script src="<?=$init->path();?>js/nicescroll/jquery.nicescroll.min.js"></script>
  <!-- icheck -->
  <script src="<?=$init->path();?>js/icheck/icheck.min.js"></script>

  <script src="<?=$init->path();?>js/custom.js"></script>
  <!-- form wizard -->
  <script type="text/javascript" src="<?=$init->path();?>js/wizard/jquery.smartWizard.js"></script>
  <!-- pace -->
  <script src="<?=$init->path();?>js/pace/pace.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
		// Smart Wizard
		$('#wizard_verticle').smartWizard({
			contentURL: './install.php?ajax=true&atype=install::content',
			selected: 0,
			contentCache:false,
			toolbarSettings: {
				toolbarButtonPosition: 'right',
				showNextButton: true,
                showPreviousButton: true,
				toolbarPosition: 'bottom',
			},
			onFinish: function() {
				$.post('http://<?=$_SERVER['SERVER_NAME'];?>:<?=$_SERVER['SERVER_PORT'];?>/<?=$_SERVER['SCRIPT_NAME'];?>/install.php?ajax=true',{
					atype:'install::content',
					response:200,
					dbhost:$('#host').val(),
					dbname:$('#name').val(),
					dbuser:$('#user').val(),
					dbpass:$('#dbpass').val(),
					ufname:$('#first-name2').val(),
					ulname:$('#last-name2').val(),
					uemail:$('#email').val(),
					upassw:$('#pass').val(),
					urtpsw:$('#rtp-pass').val(),
				},function(data){
					alert('test');
				});
			},
			transitionEffect: 'slide'
		});
    });
  </script>

</body>

</html>
