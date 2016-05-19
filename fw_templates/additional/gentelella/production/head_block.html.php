<?php
$seo = new headerSeo;
$seo->encode();
$seo->custom('<meta http-equiv="X-UA-Compatible" content="IE=edge">');
$seo->responsive();
$seo->css("{$csspath}css/bootstrap.min.css");
$seo->css("{$csspath}fonts/css/font-awesome.min.css");
$seo->css("{$csspath}css/animate.min.css");
$seo->css("{$csspath}css/custom.css");
$seo->css("{$csspath}css/icheck/flat/green.css");
$seo->script("{$jspath}js/jquery.min.js");
$seo->title($core->coreSencillo->info["FWK"]);
$seo->owner("'.$_POST['user-new-name'].', '.$_POST['user-new-mail'].'");
echo $seo->save();