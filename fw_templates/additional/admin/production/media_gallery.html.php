<?php
$openfolder = str_ireplace($this->mediapath,'',$_POST['dirselector']).'/';
$this->setupGallery($openfolder);
$this->getImagesSubDirs();
foreach($this->imagessubdirs as $val)
{
	$options[]='<option value="'.$val.'"'.($val==$_POST['dirselector']?' selected':'').'>'.$val.'</option>'.PHP_EOL;
}
?>
		<div class="row">
			<form action="<?=$this->server_url.'/gallery';?>" method="post">
			<div class="col-md-12 col-sm-12 col-xs-12">
			  <div class="x_panel">
				<div class="x_title">
				  <h2>Target folder</h2>
				  <div class="clearfix"></div>
				</div>
				<div class="x_content">
				  
					<div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="dirselector">Select folder for upload</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="dirselector" name="dirselector" class="form-control col-md-7 col-xs-12">
							<option>-- SELECT DIRECTORY --</option>
							<?=implode('',$options);?>
						</select>
                      </div>
                    </div>
				  
				</div>
				<div class="form-group">
				  <div class="col-md-6">
				    <button type="submit" class="btn btn-primary">Open folder</button>
				  </div>
			    </div>
			  </div>
			</div>
			</form>
			<div class="col-md-12 col-sm-12 col-xs-12">
			  <div class="x_panel">
				<div class="x_title">
				  <h2>Multiple file uploader</h2>
				  <div class="clearfix"></div>
				</div>
				<div class="x_content">

				  <p>Drag multiple files to the box below for multi upload or click to select files.</p>
				  <form action="<?=$this->server_url;?>/ajax.slot.php" class="dropzone dz-clickable" style="border: 1px solid #e5e5e5; min-height: 150px; ">
					<div class="dz-default dz-message">
						<span>Drop files here to upload</span>
					</div>
					<input type="hidden" name="atype" value="gallery::fileupload">
					<input type="hidden" name="dirselector" value="<?=$_POST['dirselector'];?>">
				  </form>
				</div>
			  </div>
			</div>
          <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Gallery <small><?=$_POST['dirselector'];?></small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                  <div class="row">
<?php
foreach($this->images as $key=>$val)
{
	
	if(($key>1)&&((mb_stripos($val,'.png'))||(mb_stripos($val,'.jpg'))||(mb_stripos($val,'.jpeg'))||(mb_stripos($val,'.svg'))))
	{
		echo '				<div class="col-md-55" id="'.md5($val).'">
							  <div class="thumbnail">
								<div class="image view view-first">
								  <img style="height: 100%; display: block; margin-left: auto; margin-right: auto;" src="'.$this->mediapath.$openfolder.$val.'" alt="image">
								  <div class="mask">
									<p>&nbsp;</p>
									<div class="tools tools-bottom">
									  <a href="'.$this->mediapath.$openfolder.$val.'"><i class="fa fa-link"></i></a>
									  <a class="remove-image" href="#remove" data-remove="'.md5($val).'" data-path="'.$this->mediapath.$openfolder.$val.'"><i class="fa fa-trash"></i></a>
									  <!--<a href="#"><i class="fa fa-times"></i></a>-->
									</div>
								  </div>
								</div>
								<div class="caption">
								  <p>'.$val.'</p>
								</div>
							  </div>
							</div>'.PHP_EOL;
	}
}
?>

                  </div>

                </div>
              </div>
            </div>

        </div>

		<!--<script src="<?=$this->js;?>/js/bootstrap.min.js"></script>-->

		<!-- bootstrap progress js -->
		<script src="<?=$this->js;?>/js/progressbar/bootstrap-progressbar.min.js"></script>
		<script src="<?=$this->js;?>/js/nicescroll/jquery.nicescroll.min.js"></script>
		<!-- upload drop zone -->
		<script src="<?=$this->js;?>/js/dropzone/dropzone.js"></script>
		<!-- icheck -->
		<script src="<?=$this->js;?>/js/icheck/icheck.min.js"></script>
		<script src="<?=$this->js;?>/js/custom.js"></script>
		<!-- pace -->
		<script src="<?=$this->js;?>/js/pace/pace.min.js"></script>

