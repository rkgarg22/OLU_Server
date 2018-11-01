<?php 

echo"Login : ". $login  = "fcec4c9fd9ea26079d9302b2424d38ea";
echo "<br>";
echo  "Seed : ". $seed = date('c');
echo "<br>";
if (function_exists('random_bytes')) {
    $nonce = bin2hex(random_bytes(16));
} elseif (function_exists('openssl_random_pseudo_bytes')) {
    $nonce = bin2hex(openssl_random_pseudo_bytes(16));
} else {
    $nonce = mt_rand();
}
echo $nonce;
echo "<br>";
echo "Nonce : ".$nonceBase64 = base64_encode($nonce);
echo "<br>";
echo "Trankey : ". $tranKey = base64_encode(sha1($nonce . $seed . "92EukRSJ82Vr0TUt", true));
$ch = curl_init();
echo $arrayJson = '{ "auth": {"login": "'. $login .'", "seed" : "'. $seed .'", "nonce" :"'. $nonceBase64 .'" ,  "tranKey" :"'. $tranKey .'" }}';
echo "<pre>";
    print_r(json_decode($arrayJson));
echo "</pre>";
/* //set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, "https://test.placetopay.com/redirection/api/session/");
curl_setopt($ch, CURLOPT_POST, count(json_decode($arrayJson)));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_decode($arrayJson));

//execute post
$result = curl_exec($ch);
echo "<pre>";
print_r($result);
echo "</pre>";
//close connection
curl_close($ch); */

?>