<?php
$installContent[0]='<h2 class="StepTitle">Step 1</h2>
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
                      </form>';

$installContent[1]='<h2 class="StepTitle">Step 2</h2>
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
                            <input type="password" id="dbpass" name="dbpass" class="form-control col-md-7 col-xs-12" required="required">
                          </div>
                        </div>
                      </form>';

$installContent[2]='<h2 class="StepTitle">Step 3</h2>
                      <div class="form-horizontal form-label-left">
                        <span class="section">Installing file system</span>
                      </div><div class="form-horizontal form-label-left">
                        <span class="">Creating configuration files ... <strong>DONE!</strong></span>
                      </div><div class="form-horizontal form-label-left">
                        <span class="">After Installation processing  ... <strong>DONE!</strong></span>
                      </div><div class="form-horizontal form-label-left">
                        <span class="">CHMOD configuration  ... <strong>DONE!</strong></span>
                      </div>';

$installContent[3]='<h2 class="StepTitle">Step 4</h2>
                      <div class="form-horizontal form-label-left">
                        <span class="section">Installing database</strong></span>
                      </div><div class="form-horizontal form-label-left">
                        <span class="">Validating DB config ... <strong>DONE!</strong></span>
                      </div><div class="form-horizontal form-label-left">
                        <span class="">Connecting ... <strong>DONE!</strong></span>
                      </div><div class="form-horizontal form-label-left">
                        <span class="">Creating structures ... <strong>DONE!</strong></span>
                      </div><div class="form-horizontal form-label-left">
                        <span class="">Insert data content ... <strong>DONE!</strong></span>
                      </div><div class="form-horizontal form-label-left">
                        <span class="">DB configuration process ... <strong>DONE!</strong></span>
                      </div>';
					  
$installContent['err']['installed']='<h2 class="StepTitle">Error I:01</h2>
                      <div class="form-horizontal form-label-left">
                        <span class="section">Other installed Sencillo or OpenSencillo detected!</span>
                      </div>';

$installContent['err']['phpversion']='<h2 class="StepTitle">Error I:02</h2>
                      <div class="form-horizontal form-label-left">
                        <span class="section">Detected unsupported PHP '.(int)phpversion().' version (PHP 7.1 required)!</span>
                      </div>';

$installContent['err']['unknown-fs']='<h2 class="StepTitle">Error I:03</h2>
                      <div class="form-horizontal form-label-left">
                        <span class="section">Unknown error in file system!</span>
                      </div>';

$installContent['err']['unknown-db']='<h2 class="StepTitle">Error I:04</h2>
                      <div class="form-horizontal form-label-left">
                        <span class="section">Unknown error in database system!</span>
                      </div>';
?>
