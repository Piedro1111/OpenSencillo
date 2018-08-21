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
                      <h2 class="StepTitle">Step 1</h2>
                      <form class="form-horizontal form-label-left">
                        <span class="section">Superuser</span>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3" for="first-name">First Name <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6">
                            <input type="text" id="first-name2" required="required" class="form-control col-md-7 col-xs-12">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3" for="last-name">Last Name <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6">
                            <input type="text" id="last-name2" name="last-name" required="required" class="form-control col-md-7 col-xs-12">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="email" class="control-label col-md-3 col-sm-3">Email <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6">
                            <input id="email" class="form-control col-md-7 col-xs-12" required="required" type="text" name="email">
                          </div>
                        </div>
						<div class="form-group">
                          <label for="pass" class="control-label col-md-3 col-sm-3">Password <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6">
                            <input id="pass" class="form-control col-md-7 col-xs-12" required="required" type="password" name="pass">
                          </div>
                        </div>
						<div class="form-group">
                          <label for="rtp-pass" class="control-label col-md-3 col-sm-3">Retype <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6">
                            <input id="rtp-pass" class="form-control col-md-7 col-xs-12" required="required" type="password" name="rtp-pass">
                          </div>
                        </div>
                      </form>
                    </div>
                    <div id="step-22">
                      <h2 class="StepTitle">Step 2</h2>
                      <form class="form-horizontal form-label-left">
                        <span class="section">SQL Database</span>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3" for="host">Host <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6">
                            <input type="text" id="host" name="host" required="required" class="form-control col-md-7 col-xs-12">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3" for="dbname">Name <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6">
                            <input type="text" id="name" name="name" required="required" class="form-control col-md-7 col-xs-12">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="user" class="control-label col-md-3 col-sm-3">User <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6">
                            <input type="text" id="user" name="user" class="form-control col-md-7 col-xs-12" required="required">
                          </div>
                        </div>
						<div class="form-group">
                          <label for="dbpass" class="control-label col-md-3 col-sm-3">Password <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6">
                            <input type="password" id="pass" name="pass" class="form-control col-md-7 col-xs-12" required="required">
                          </div>
                        </div>
                      </form>
                    </div>
                    <div id="step-33">
                      <h2 class="StepTitle">Step 3</h2>
					  <span class="section">Chmod validation</span>
                      <p id="step-3-chmod">Loading ...</p>
                    </div>
                    <div id="step-44">
                      <h2 class="StepTitle">Step 4</h2>
					  <span class="section">Installation</span>
                      <p id="step-4-install">Loading ...</p>
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
      $('#wizard').smartWizard();

      function onFinishCallback() {
        $('#wizard').smartWizard('showMessage', 'Finish Clicked');
        //alert('Finish Clicked');
      }
    });

    $(document).ready(function() {
      // Smart Wizard
      $('#wizard_verticle').smartWizard({
        transitionEffect: 'slide'
      });

    });
  </script>

</body>

</html>
