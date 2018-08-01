<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
require_once("./basicstrap.php");
admin::ajax();
pihome::ajax();
?>