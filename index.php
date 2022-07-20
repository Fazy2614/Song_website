<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include("header.php");
include("config/Database.php");
include_once("config/func.php");
$page = $_GET['p'];
if(!empty($page)){
    include($page.".php");
}else{
    include("login.php");
}

?>