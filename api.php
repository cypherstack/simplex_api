<?php

require_once("simplex_api.php");
// TODO test functions

$path = explode('/',$_SERVER["REQUEST_URI"]);
$path = array_key_exists(2, $path) ? $path[2] : $path[1];
$path = strpos($path, '?') ? substr($path, 0, strpos($path, '?')) : $path;
// TODO error handle if no path given

$response = '';
$error = false;

switch($path) {
    case 'supported_cryptos':
        try {
            $cryptos = supported_cryptos();
            $response = $cryptos;
        } catch (Exception $e) {
            $error = true;
            var_dump($e->getMessage());
        }
        break;
    case 'supported_fiats':
        try {
            $fiats = supported_fiats();
            $response = $fiats;
        } catch (Exception $e) {
            $error = true;
            var_dump($e->getMessage());
        }
        break;
    case 'quote':
        try {
            $quote = get_quote();
            $results = $quote;
        } catch (Exception $e) {
            $error = true;
            var_dump($e->getMessage());
        }
        break;
    case 'order':
        try {
            $quote = get_quote();
            $order = place_order();
            $resposne = $order;
        } catch (Exception $e) {
            $error = true;
            var_dump($e->getMessage());
        }
        break;
    default:
        $response = json_encode(array('error' => 'true', 'message' => 'Error redirecting to checkout'));
        break;
}

if (!$error) {
    header('Content-Type: application/json; charset=utf-8');
}
echo $response;

?>
