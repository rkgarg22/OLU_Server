<?php 
include("../../wp-config.php");

$getPaymerDetails = getPaymerDetailsTest(182);
echo "<pre>";
    print_r($getPaymerDetails->subscription->instrument[0]->value);
echo "</pre>";