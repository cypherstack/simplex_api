<?php

require_once("simplex_api.php");
// TODO test functions

// $route = isset($_REQUEST['ROUTE']) ? $_REQUEST['ROUTE'] : null;
$route = explode('/',$_SERVER["REQUEST_URI"]);
$route = array_key_exists(2, $route) ? $route[2] : $route[1];
$route = strpos($route, '?') ? substr($route, 0, strpos($route, '?')) : $route;
// TODO error handle if no path given

$response = null;
$error = false;
$redirect = false;

// TODO refactor all $_REQUEST handling into here

switch($route) {
    case 'supported_cryptos':
        try {
            $_API_KEY = isset($_REQUEST['API_KEY']) ? $_REQUEST['API_KEY'] : $API_KEY;
            $cryptos = supported_cryptos($_API_KEY);
            $response = $cryptos;
        } catch (Exception $e) {
            // $error = true;
            $response = json_encode(array('error' => 'true', 'message' => $e->getMessage()));
        }
        break;
    case 'supported_fiats':
        try {
            $_API_KEY = isset($_REQUEST['API_KEY']) ? $_REQUEST['API_KEY'] : $API_KEY;
            $fiats = supported_fiats($_API_KEY);
            $response = $fiats;
        } catch (Exception $e) {
            // $error = true;
            $response = json_encode(array('error' => 'true', 'message' => $e->getMessage()));
        }
        break;
    case 'quote':
        try {
            $_FIAT_TICKER = isset($_REQUEST['FIAT_TICKER']) ? $_REQUEST['FIAT_TICKER'] : $FIAT_TICKER;
            $_CRYPTO_TICKER = isset($_REQUEST['CRYPTO_TICKER']) ? $_REQUEST['CRYPTO_TICKER'] : $CRYPTO_TICKER;
            $_REQUESTED_TICKER = isset($_REQUEST['REQUESTED_TICKER']) ? $_REQUEST['REQUESTED_TICKER'] : $REQUESTED_TICKER;
            $_REQUESTED_AMOUNT = isset($_REQUEST['REQUESTED_AMOUNT']) ? $_REQUEST['REQUESTED_AMOUNT'] : $REQUESTED_AMOUNT;
            $_USER_ID = isset($_REQUEST['USER_ID']) ? $_REQUEST['USER_ID'] : $USER_ID;
            $_WALLET_ID = isset($_REQUEST['WALLET_ID']) ? $_REQUEST['WALLET_ID'] : $WALLET_ID;
            $_API_KEY = isset($_REQUEST['API_KEY']) ? $_REQUEST['API_KEY'] : $API_KEY;
            $quote = get_quote($_FIAT_TICKER, $_CRYPTO_TICKER, $_REQUESTED_TICKER, $_REQUESTED_AMOUNT, $_USER_ID, $_WALLET_ID, $_API_KEY);
            $response = $quote;
        } catch (Exception $e) {
            // $error = true;
            $response = json_encode(array('error' => 'true', 'message' => $e->getMessage()));
        }
        break;
    case 'order':
        try {
            $_QUOTE_ID = isset($_REQUEST['QUOTE_ID']) ? $_REQUEST['QUOTE_ID'] : $QUOTE_ID;
            if (!$_QUOTE_ID) {
                throw new Exception('Quote ID not provided');
            }
            $_ADDRESS = isset($_REQUEST['ADDRESS']) ? $_REQUEST['ADDRESS'] : $ADDRESS;
            $_CRYPTO_TICKER = isset($_REQUEST['CRYPTO_TICKER']) ? $_REQUEST['CRYPTO_TICKER'] : $CRYPTO_TICKER;
            // TODO sanitize $_REQUEST inputs above
            // $quote = get_quote();
            $order = place_order($_QUOTE_ID, $_ADDRESS, $_CRYPTO_TICKER);
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
            if (!isset($_REQUEST['PAYMENT_ID'])) {
                $response = json_encode(array('error' => 'true', 'message' => 'Payment ID needed to redirect'));
                break;
            }
            $_PAYMENT_ID = isset($_REQUEST['PAYMENT_ID']) ? $_REQUEST['PAYMENT_ID'] : $PAYMENT_ID;
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
    // header('Content-Type: application/json; charset=utf-8');
}
echo $response;

?>
