<?php
$seo = new headerSeo;
$seo->encode();
$seo->custom('<meta http-equiv="X-UA-Compatible" content="IE=edge">');
$seo->responsive();
$seo->css("{$this->css}css/bootstrap.min.css");
$seo->css("{$this->css}fonts/css/font-awesome.min.css");
$seo->css("{$this->css}css/animate.min.css");
$seo->css("{$this->css}css/custom.css");
$seo->css("{$this->css}css/icheck/flat/green.css");
$seo->script("{$this->js}js/jquery.min.js");
$seo->script("{$this->js}js/extend_js/ext.js");
$seo->title($core->coreSencillo->info["FWK"]);
$seo->owner("'.$_POST['user-new-name'].', '.$_POST['user-new-mail'].'");
echo $seo->save();