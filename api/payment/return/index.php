<?php 
$rest = json_decode(file_get_contents('php://input'), true);
print_r($rest);

$val = sha1($rest['requestId'] . $rest['status']['status'] . $rest['status']['date'] . '024h1IlD');

if ($val == $rest['signature']) {


    echo $rest['requestId'];

} else {

    echo '<br>';
    echo 'Generado: ' . $val;
    echo '<br>';
    echo 'muestra: feb3e7cc76939c346f9640573a208662f30704ab';
    echo '<br>';
    echo 'recibido: ' . $rest['signature'];
} 
?>