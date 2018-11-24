<?php
echo  "aaaaaaaaaaaaaaaaa";
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("America/Bogota");
$current = date("Y-m-d H:i:s");
include("../../../wp-config.php");
echo generateMyRefNumber();