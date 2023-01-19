<?php

require_once("simplex_api.php");

$response = '';
$error = false;
$message = null;

try {
    $cryptos = supported_cryptos();
    $response = $cryptos;
} catch (Exception $e) {
    $error = true;
    var_dump($e->getMessage());
}

$response = json_encode(array('error' => $error, 'message' => $message, 'status' => ($error) ? 'offline' : 'online', 'response' => json_decode($response)));
if (!$error) {
    header('Content-Type: application/json; charset=utf-8');
}
echo $response;

?>
