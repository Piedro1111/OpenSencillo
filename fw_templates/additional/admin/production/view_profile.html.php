        <div class="row">


          <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2><?=$this->permDecode($this->profile('perm'));?> <small><?=$this->profile('login');?></small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                  <form class="form-horizontal form-label-left" novalidate>
                    <span class="section">Personal Info</span>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input class="form-control col-md-7 col-xs-12" required="required" disabled type="text" value="<?=$this->profile('fname').' '.$this->profile('lname');?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="email" required="required" class="form-control col-md-7 col-xs-12" disabled value="<?=$this->profile('email');?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="number">Permission <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="number" required="required" class="form-control col-md-7 col-xs-12" disabled value="<?=$this->profile('perm');?>">
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

