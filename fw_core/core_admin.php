<?php
$translate_admin = new translate;
$logman_admin = new logMan($DBHost,$DBName,$DBUser,$DBPass);
$seo_admin = new headerSeo;
$logman_admin->adminLogin($translate_admin,$seo_admin);
?>