<?php

require_once("simplex_api.php");
// TODO test functions

$path = explode('/',$_SERVER["REQUEST_URI"]);
$path = array_key_exists(2, $path) ? $path[2] : $path[1];
$path = strpos($path, '?') ? substr($path, 0, strpos($path, '?')) : $path;
// TODO error handle if no path given

$response = null;
$error = false;
$redirect = false;

switch($path) {
    case 'supported_cryptos':
        try {
            $cryptos = supported_cryptos();
            $response = $cryptos;
        } catch (Exception $e) {
            // $error = true;
            $response = json_encode(array('error' => 'true', 'message' => $e->getMessage()));
        }
        break;
    case 'supported_fiats':
        try {
            $fiats = supported_fiats();
            $response = $fiats;
        } catch (Exception $e) {
            // $error = true;
            $response = json_encode(array('error' => 'true', 'message' => $e->getMessage()));
        }
        break;
    case 'quote':
        try {
            $quote = get_quote();
            $response = $quote;
        } catch (Exception $e) {
            // $error = true;
            $response = json_encode(array('error' => 'true', 'message' => $e->getMessage()));
        }
        break;
    case 'order':
        try {
            // $quote = get_quote();
            $order = place_order();
            // $order = json_decode($order);
            // $order->quote = json_decode($quote);
            // $order = json_encode($order);
            $response = $order;
        } catch (Exception $e) {
            // $error = true;
            $response = json_encode(array('error' => 'true', 'message' => $e->getMessage()));
        }
        break;
    case 'redirect':
        try {
            if (!array_key_exists('PAYMENT_ID', $_REQUEST)) {
                $response = json_encode(array('error' => 'true', 'message' => 'Payment ID needed to redirect'));
                break;
            }
            $_PAYMENT_ID = array_key_exists('PAYMENT_ID', $_REQUEST) ? $_REQUEST['PAYMENT_ID'] : $PAYMENT_ID;
            // TODO sanitize $_REQUEST input
            $reponse = redirect($_PAYMENT_ID);
            $redirect = true;
        } catch (Exception $e) {
            // $error = true;
            $response = json_encode(array('error' => 'true', 'message' => $e->getMessage()));
        }
        break;
    case 'success':
        try {
            $reponse = success();
            $redirect = true;
            if (!gettype($response) === 'string') {
                if (!array_key_exists('error', $reponse)) { // TODO correct error detection and handling here
                    if ($response['error']) {
                        $redirect = false;
                    }
                }
            }
        } catch (Exception $e) {
            // $error = true;
            $response = json_encode(array('error' => 'true', 'message' => $e->getMessage()));
        }
        break;
    case 'failure':
        try {
            $reponse = failure();
            $redirect = true;
            if (!gettype($response) === 'string') {
                if (!array_key_exists('error', $reponse)) { // TODO correct error detection and handling here
                    if ($response['error']) {
                        $redirect = false;
                    }
                }
            }
        } catch (Exception $e) {
            // $error = true;
            $response = json_encode(array('error' => 'true', 'message' => $e->getMessage()));
        }
        break;
    default:
        // $error = true;
        $response = json_encode(array('error' => 'true', 'message' => 'No route provided'));
        break;
}

if ((!$error && !$redirect) || ($response === FALSE || is_null($reponse))) {
    header('Content-Type: application/json; charset=utf-8');
}
echo $response;

?>
