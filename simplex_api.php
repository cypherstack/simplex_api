<?php

// See README.md for usage
// Methods:
// get_supported_cryptos()
// get_supported_fiats()

// Set these values in config.php, which is included and overwrites these below
// -----------------------------------------------------------------------------
// $API_KEY = 'API Key';
// $PUBLIC_KEY = 'Public Key';
// $WALLET_ID = 'Wallet ID';
// $REFERRER = 'https://example.com/simplex';
// $REFERRAL_IP = '8.8.8.8';

// $RETURN_URL_SUCCESS = 'https://example.com/success';
// $RETURN_URL_FAILURE = 'https://example.com/failure';
// -----------------------------------------------------------------------------
include 'config.php';

// TODO get all of the below from parameters/wallet
$CRYPTO_TICKER = 'BTC';
$CRYPTO_AMOUNT = 0.00411956;
$FIAT_TICKER = 'USD';
$ADDRESS = 'bc1qar0srrr7xfkvy5l643lydnw9re59gtzzwf5mdq';
$VERSION = '0.0.1'; 
$USER_ID = guidv4();
$SIGNUP_TIMESTAMP = '1994-11-05T08:15:30-05:00';

$QUOTE_ID = 'e5440c77-b669-48d5-bc5c-d2f7417434bb'; // Set later in get_quote()

// TODO save/return all of the below
$PAYMENT_ID = guidv4();
$ORDER_ID = guidv4();

// TODO validate all of the above

/**
 * Get supported cryptocurrencies
 *
 * @since 0.0.1
 *
 * @param ?string $_PUBLIC_KEY Simplex public key string. Optional; null defaults to value in config.php
 * @return json Simplex supported cryptos object
 */
function supported_cryptos(?string $_PUBLIC_KEY = null) {
    // curl --request GET \
    //      --url 'https://sandbox.test-simplexcc.com/v2/supported_crypto_currencies?public_key=$public_key' \
    //      --header 'accept: application/json'

    global $PUBLIC_KEY;
    $_PUBLIC_KEY = is_null($_PUBLIC_KEY) ? array_key_exists('PUBLIC_KEY', $_REQUEST) ? $_REQUEST['PUBLIC_KEY'] : $PUBLIC_KEY : $_PUBLIC_KEY;
    // TODO sanitize $_REQUEST inputs above

    $url = "https://sandbox.test-simplexcc.com/v2/supported_crypto_currencies?public_key=$_PUBLIC_KEY";
    $options = array(
        'http' => array(
            'header'  => "Accept: application/json\r\n",
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false );
    if ($result === FALSE) {
        return json_encode(array('error' => 'true', 'message' => 'Error calling supported_crypto_currencies'));
    } else {
        return $result;
    }
}

/**
 * Get supported fiat currencies
 *
 * @since 0.0.1
 *
 * @param ?string $_PUBLIC_KEY Simplex public key string. Optional, null defaults to value in config.php
 * @return json Simplex supported cryptos object
 */
function supported_fiats(?string $_PUBLIC_KEY = null) {
    // curl --request GET \
    //      --url 'https://sandbox.test-simplexcc.com/v2/supported_fiat_currencies?public_key=$public_key' \
    //      --header 'accept: application/json'

    global $PUBLIC_KEY;
    $_PUBLIC_KEY = is_null($_PUBLIC_KEY) ? array_key_exists('PUBLIC_KEY', $_REQUEST) ? $_REQUEST['PUBLIC_KEY'] : $PUBLIC_KEY : $_PUBLIC_KEY;
    // TODO sanitize $_REQUEST inputs above

    $url = "https://sandbox.test-simplexcc.com/v2/supported_fiat_currencies?public_key=$_PUBLIC_KEY";
    $options = array(
        'http' => array(
            'header'  => "Accept: application/json\r\n",
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false );
    if ($result === FALSE) {
        return json_encode(array('error' => 'true', 'message' => 'Error calling supported_fiat_currencies'));
    } else {
        return $result;
    }
}

/**
 * Get a quote from Simplex
 *
 * @since 0.0.1
 *
 * @param string $USER_ID 
 * @param string $CRYPTO_TICKER
 * @param string FIAT_TICKER  
 * @param float $CRYPTO_AMOUNT 
 * @param string $WALLET_ID 
 * @param string $REFERRAL_IP 
 * @param string $API_KEY 
 * @param string $QUOTE_ID 
 * @return json Simplex quote object
 */
function get_quote() {
    // curl --request POST \
    //      --url https://sandbox.test-simplexcc.com/wallet/merchant/v2/quote \
    //      --header 'Authorization: ApiKey $API_KEY' \
    //      --header 'accept: application/json' \
    //      --header 'content-type: application/json' \
    //      -d '{"end_user_id": "9e4ba9c9-5a06-4a1e-8e1c-ad096b31543d", "digital_currency": "BTC", "fiat_currency": "USD", "requested_currency": "BTC", "requested_amount": 0.00411956, "wallet_id": "stackwalet", "client_ip": "207.66.86.226"}'

    global $USER_ID, $CRYPTO_TICKER, $FIAT_TICKER, $CRYPTO_AMOUNT, $WALLET_ID, $REFERRAL_IP, $API_KEY, $QUOTE_ID;
    
    $url = 'https://sandbox.test-simplexcc.com/wallet/merchant/v2/quote';
    $data = array(
        'end_user_id' => $USER_ID,
        'digital_currency' => $CRYPTO_TICKER,
        'fiat_currency' => $FIAT_TICKER,
        'requested_currency' => $CRYPTO_TICKER,
        'requested_amount' => $CRYPTO_AMOUNT,
        'wallet_id' => $WALLET_ID,
        'client_ip' => $REFERRAL_IP,
    );
    $options = array(
        'http' => array(
            'method'  => 'POST',
            'header'  => "Authorization: ApiKey $API_KEY\r\nContent-type: application/json\r\nAccept: application/json\r\n",
            'content' => json_encode($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) {
        return json_encode(array('error' => 'true', 'message' => 'Error getting quote'));
    } else {
        $json_result = json_decode($result);
        $QUOTE_ID = $json_result->quote_id; // Update global quote ID so we can get_quote(); place_order(); for debugging purposes
        return $result;
    }
}

/**
 * Get a quote from Simplex
 *
 * @since 0.0.1
 *
 * @param string $PUBLIC_KEY
 * @param string $VERSION
 * @param string $USER_ID
 * @param string $SIGNUP_TIMESTAMP
 * @param string $REFERRAL_IP
 * @param string $QUOTE_ID
 * @param string $PAYMENT_ID
 * @param string $ORDER_ID
 * @param string $REFERRER
 * @param string $CRYPTO_TICKER
 * @param string $ADDRESS
 * @param string $API_KEY
 * @return json Simplex order object
 */
function place_order() {
    // curl --request POST \
    //      --url https://sandbox.test-simplexcc.com/wallet/merchant/v2/payments/partner/data \
    //      --header 'Authorization: ApiKey $API_KEY' \
    //      --header 'accept: application/json' \
    //      --header 'content-type: application/json' \
    //      -d '{"account_details": {"app_provider_id": "$PUBLIC_KEY", "app_version_id": "123", "app_end_user_id": "01e7a0b9-8dfc-4988-a28d-84a34e5f0a63", "signup_login": {"timestamp": "1994-11-05T08:15:30-05:00", "ip": "207.66.86.226"}}, "transaction_details": {"payment_details": {"quote_id": "3b58f4b4-ed6f-447c-b96a-ffe97d7b6803", "payment_id": "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa", "order_id": "789", "original_http_ref_url": "https://stackwallet.com/simplex", "destination_wallet": {"currency": "BTC", "address": "bc1qar0srrr7xfkvy5l643lydnw9re59gtzzwf5mdq"}}}}'

    global $PUBLIC_KEY, $VERSION, $USER_ID, $SIGNUP_TIMESTAMP, $REFERRAL_IP, $QUOTE_ID, $PAYMENT_ID, $ORDER_ID, $REFERRER, $CRYPTO_TICKER, $ADDRESS, $API_KEY;
    $url = 'https://sandbox.test-simplexcc.com/wallet/merchant/v2/payments/partner/data';
    $data = array(
        'account_details' => array(
            'app_provider_id' => $PUBLIC_KEY,
            'app_version_id' => $VERSION,
            'app_end_user_id' => $USER_ID,
            'signup_login' => array(
                'timestamp' => $SIGNUP_TIMESTAMP,
                'ip' => $REFERRAL_IP,
            )
        ),
        'transaction_details' => array(
            'payment_details' => array(
                'quote_id' => $QUOTE_ID,
                'payment_id' => $PAYMENT_ID,
                'order_id' => $ORDER_ID,
                'original_http_ref_url' => $REFERRER,
                'destination_wallet' => array(
                    'currency' => $CRYPTO_TICKER,
                    'address' => $ADDRESS,
                )
            )
        )
    );
    $options = array(
        'http' => array(
            'method'  => 'POST',
            'header'  => "Authorization: ApiKey $API_KEY\r\nContent-type: application/json\r\nAccept: application/json\r\n",
            'content' => json_encode($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) {
        return json_encode(array('error' => 'true', 'message' => 'Error placing order'));
    } else {
        return $result;
    }
}

/**
 * Redirect to Simplex checkout page
 *
 * @since 0.0.1
 *
 * @param string $_PAYMENT_ID Payment ID to which to redirect
 * @param ?string $_RETURN_URL_SUCCESS URL to which to redirect upon success. Optional, null defaults to value in config.php
 * @param ?string $_RETURN_URL_FAIL URL to which to redirect upon failure. Optional, null defaults to value in config.php
 * @param ?string $_WALLET_ID Partner/wallet ID from Simplex.  Defaults. Optional, null defaults to value in config.php
 * @return dynamic String or json error object
 */
function redirect(
    string $_PAYMENT_ID,
    ?string $_RETURN_URL_SUCCESS = null,
    ?string $_RETURN_URL_FAIL = null,
    ?string $_WALLET_ID = null
) {
    global $PUBLIC_KEY, $VERSION, $USER_ID, $SIGNUP_TIMESTAMP, $REFERRAL_IP, $QUOTE_ID, $PAYMENT_ID, $ORDER_ID, $REFERRER, $CRYPTO_TICKER, $ADDRESS, $API_KEY;
    $_PAYMENT_ID = is_null($_PAYMENT_ID) ? array_key_exists('_PAYMENT_ID', $_REQUEST) ? $_REQUEST['_PAYMENT_ID'] : $_PAYMENT_ID : $PAYMENT_ID;
    $_RETURN_URL_SUCCESS = is_null($_RETURN_URL_SUCCESS) ? array_key_exists('_RETURN_URL_SUCCESS', $_REQUEST) ? $_REQUEST['_RETURN_URL_SUCCESS'] : $_RETURN_URL_SUCCESS : $RETURN_URL_SUCCESS;
    $_RETURN_URL_FAIL = is_null($_RETURN_URL_FAIL) ? array_key_exists('_RETURN_URL_FAIL', $_REQUEST) ? $_REQUEST['_RETURN_URL_FAIL'] : $_RETURN_URL_FAIL : $RETURN_URL_FAIL;
    $_WALLET_ID = is_null($_WALLET_ID) ? array_key_exists('_WALLET_ID', $_REQUEST) ? $_REQUEST['_WALLET_ID'] : $_WALLET_ID : $WALLET_ID;
    // TODO sanitize $_REQUEST inputs above

    try {
        include 'templates/redirect.php';
        $response = redirect_template($_PAYMENT_ID, $_RETURN_URL_SUCCESS, $_RETURN_URL_FAIL, $_WALLET_ID);
    } catch (Exception $e) {
        $response = json_encode(array('error' => 'true', 'message' => 'Internal error redirecting to checkout'));
        var_dump($e->getMessage());
    }
    return $response;
}

/**
 * Show Simplex checkout success page
 *
 * @since 0.0.1
 * @return dynamic String or json error object
 */
function success() {
    // TODO take parameters to show printable receipt

    try {
        require_once 'templates/success.php';
        $response = success_template();
    } catch (Exception $e) {
        $response = json_encode(array('error' => 'true', 'message' => 'Internal error showing checkout success page'));
        var_dump($e->getMessage());
    }
    return $response;
}

/**
 * Show Simplex checkout failure page
 *
 * @since 0.0.1
 * @return dynamic String or json error object
 */
function failure() {
    // TODO take parameters to errors
    try {
        require_once 'templates/failure.php';
        $response = failure_template();
    } catch (Exception $e) {
        $response = json_encode(array('error' => 'true', 'message' => 'Internal error showing checkout failure page'));
        var_dump($e->getMessage());
    }
    return $response;
}

function guidv4() { // See https://stackoverflow.com/a/15875555
    $data = openssl_random_pseudo_bytes(16);

    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

?>
