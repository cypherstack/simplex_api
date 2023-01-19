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
$CRYPTO_AMOUNT = 0.0042779;
$FIAT_TICKER = 'USD';
$ADDRESS = 'bc1qar0srrr7xfkvy5l643lydnw9re59gtzzwf5mdq'; // TODO change to a good donation address; should never be used
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

    // If $_PUBLIC_KEY not passed, use value in $_REQUEST (if it's there) or use global $PUBLIC_KEY as default
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
    try {
        $result = file_get_contents($url, false, $context);
    } catch (Exception $e) {
        // $error = true;
        $result = json_encode(array('error' => 'true', 'message' => $e->getMessage()));
    }
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
    try {
        $result = file_get_contents($url, false, $context);
    } catch (Exception $e) {
        // $error = true;
        $result = json_encode(array('error' => 'true', 'message' => $e->getMessage()));
    }
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
 * @param ?string $_FIAT_TICKER 
 * @param ?string $_CRYPTO_TICKER 
 * @param ?float $_CRYPTO_AMOUNT 
 * @param ?string $_USER_ID 
 * @param ?string $_WALLET_ID 
 * @param ?string $_API_KEY 
 * @return json Simplex quote object
 */
function get_quote(
    ?string $_FIAT_TICKER = null,
    ?string $_CRYPTO_TICKER = null,
    ?float $_CRYPTO_AMOUNT = null,
    ?string $_USER_ID = null,
    ?string $_WALLET_ID = null,
    ?string $_API_KEY = null
) {
    // curl --request POST \
    //      --url https://sandbox.test-simplexcc.com/wallet/merchant/v2/quote \
    //      --header 'Authorization: ApiKey $API_KEY' \
    //      --header 'accept: application/json' \
    //      --header 'content-type: application/json' \
    //      -d '{"end_user_id": "9e4ba9c9-5a06-4a1e-8e1c-ad096b31543d", "digital_currency": "BTC", "fiat_currency": "USD", "requested_currency": "BTC", "requested_amount": 0.00411956, "wallet_id": "stackwalet", "client_ip": "207.66.86.226"}'

    global $USER_ID, $CRYPTO_TICKER, $FIAT_TICKER, $CRYPTO_AMOUNT, $WALLET_ID, $REFERRAL_IP, $API_KEY, $QUOTE_ID;
    $_FIAT_TICKER = is_null($_FIAT_TICKER) ? array_key_exists('FIAT_TICKER', $_REQUEST) ? $_REQUEST['FIAT_TICKER'] : $FIAT_TICKER : $_FIAT_TICKER;
    $_CRYPTO_TICKER = is_null($_CRYPTO_TICKER) ? array_key_exists('CRYPTO_TICKER', $_REQUEST) ? $_REQUEST['CRYPTO_TICKER'] : $CRYPTO_TICKER : $_CRYPTO_TICKER;
    $_CRYPTO_AMOUNT = is_null($_CRYPTO_AMOUNT) ? array_key_exists('CRYPTO_AMOUNT', $_REQUEST) ? $_REQUEST['CRYPTO_AMOUNT'] : $CRYPTO_AMOUNT : $_CRYPTO_AMOUNT;
    $_USER_ID = is_null($_USER_ID) ? array_key_exists('USER_ID', $_REQUEST) ? $_REQUEST['USER_ID'] : $USER_ID : $_USER_ID;
    $_WALLET_ID = is_null($_WALLET_ID) ? array_key_exists('WALLET_ID', $_REQUEST) ? $_REQUEST['WALLET_ID'] : $WALLET_ID : $_WALLET_ID;
    $_API_KEY = is_null($_API_KEY) ? array_key_exists('API_KEY', $_REQUEST) ? $_REQUEST['API_KEY'] : $API_KEY : $_API_KEY;
    // TODO sanitize $_REQUEST inputs above
    
    $url = 'https://sandbox.test-simplexcc.com/wallet/merchant/v2/quote';
    $data = array(
        'end_user_id' => $_USER_ID,
        'digital_currency' => $_CRYPTO_TICKER,
        'fiat_currency' => $_FIAT_TICKER,
        'requested_currency' => $_CRYPTO_TICKER,
        'requested_amount' => $_CRYPTO_AMOUNT,
        'wallet_id' => $_WALLET_ID,
        'client_ip' => $REFERRAL_IP,
    );
    $options = array(
        'http' => array(
            'method'  => 'POST',
            'header'  => "Authorization: ApiKey $_API_KEY\r\nContent-type: application/json\r\nAccept: application/json\r\n",
            'content' => json_encode($data)
        )
    );
    $context  = stream_context_create($options);
    try {
        $result = file_get_contents($url, false, $context);
    } catch (Exception $e) {
        // $error = true;
        $result = json_encode(array('error' => 'true', 'message' => $e->getMessage()));
    }
    if ($result === FALSE) {
        return json_encode(array('error' => 'true', 'message' => 'Error getting quote'));
    } else {
        $json_result = json_decode($result);
        $QUOTE_ID = $json_result->quote_id; // Update global quote ID so we can get_quote(); place_order(); for debugging purposes
        return $result;
    }
}

/**
 * Place an order with Simplex
 *
 * @since 0.0.1
 *
 * @param string $_QUOTE_ID
 * @param string $_ADDRESS
 * @param ?string $_PAYMENT_ID
 * @param ?string $_ORDER_ID
 * @param ?string $_CRYPTO_TICKER
 * @param ?string $_USER_ID
 * @param ?string $_SIGNUP_TIMESTAMP
 * @param ?string $_PUBLIC_KEY
 * @param ?string $_VERSION
 * @param ?string $_REFERRAL_IP
 * @param ?string $_REFERRER
 * @param ?string $_API_KEY
 * @return json Simplex order object
 */
function place_order(
    $_QUOTE_ID = null,
    $_ADDRESS = null,
    $_PAYMENT_ID = null,
    $_ORDER_ID = null,
    $_CRYPTO_TICKER = null,
    $_USER_ID = null,
    $_SIGNUP_TIMESTAMP = null,
    $_PUBLIC_KEY = null,
    $_VERSION = null,
    $_REFERRAL_IP = null,
    $_REFERRER = null,
    $_API_KEY = null
) {
    // curl --request POST \
    //      --url https://sandbox.test-simplexcc.com/wallet/merchant/v2/payments/partner/data \
    //      --header 'Authorization: ApiKey $API_KEY' \
    //      --header 'accept: application/json' \
    //      --header 'content-type: application/json' \
    //      -d '{"account_details": {"app_provider_id": "$PUBLIC_KEY", "app_version_id": "123", "app_end_user_id": "01e7a0b9-8dfc-4988-a28d-84a34e5f0a63", "signup_login": {"timestamp": "1994-11-05T08:15:30-05:00", "ip": "207.66.86.226"}}, "transaction_details": {"payment_details": {"quote_id": "3b58f4b4-ed6f-447c-b96a-ffe97d7b6803", "payment_id": "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa", "order_id": "789", "original_http_ref_url": "https://stackwallet.com/simplex", "destination_wallet": {"currency": "BTC", "address": "bc1qar0srrr7xfkvy5l643lydnw9re59gtzzwf5mdq"}}}}'

    if (is_null($_QUOTE_ID)) {
        if (!array_key_exists('QUOTE_ID', $_REQUEST)) {
            return json_encode(array('error' => 'true', 'message' => 'Error placing order, no quote ID provided'));
        }
    }
    if (is_null($_ADDRESS)) {
        if (!array_key_exists('ADDRESS', $_REQUEST)) {
            return json_encode(array('error' => 'true', 'message' => 'Error placing order, no address provided'));
        }
    }
    if (is_null($_CRYPTO_TICKER)) {
        if (!array_key_exists('ADDRESS', $_REQUEST)) {
            return json_encode(array('error' => 'true', 'message' => 'Error placing order, no crypto ticker provided'));
        }
    }

    global $PUBLIC_KEY, $VERSION, $USER_ID, $SIGNUP_TIMESTAMP, $REFERRAL_IP, $QUOTE_ID, $PAYMENT_ID, $ORDER_ID, $REFERRER, $CRYPTO_TICKER, $ADDRESS, $API_KEY;
    $_QUOTE_ID = is_null($_QUOTE_ID) ? array_key_exists('QUOTE_ID', $_REQUEST) ? $_REQUEST['QUOTE_ID'] : $QUOTE_ID : $_QUOTE_ID;
    $_ADDRESS = is_null($_ADDRESS) ? array_key_exists('ADDRESS', $_REQUEST) ? $_REQUEST['ADDRESS'] : $ADDRESS : $_ADDRESS;
    $_CRYPTO_TICKER = is_null($_CRYPTO_TICKER) ? array_key_exists('CRYPTO_TICKER', $_REQUEST) ? $_REQUEST['CRYPTO_TICKER'] : $CRYPTO_TICKER : $_CRYPTO_TICKER;
    $_PAYMENT_ID = is_null($_PAYMENT_ID) ? array_key_exists('PAYMENT_ID', $_REQUEST) ? $_REQUEST['PAYMENT_ID'] : $PAYMENT_ID : $_PAYMENT_ID;
    $_CRYPTO_TICKER = is_null($_CRYPTO_TICKER) ? array_key_exists('CRYPTO_TICKER', $_REQUEST) ? $_REQUEST['CRYPTO_TICKER'] : $CRYPTO_TICKER : $_CRYPTO_TICKER;
    $_ORDER_ID = is_null($_ORDER_ID) ? array_key_exists('ORDER_ID', $_REQUEST) ? $_REQUEST['ORDER_ID'] : $ORDER_ID : $_ORDER_ID;
    $_USER_ID = is_null($_USER_ID) ? array_key_exists('USER_ID', $_REQUEST) ? $_REQUEST['USER_ID'] : $USER_ID : $_USER_ID;
    $_SIGNUP_TIMESTAMP = is_null($_SIGNUP_TIMESTAMP) ? array_key_exists('SIGNUP_TIMESTAMP', $_REQUEST) ? $_REQUEST['SIGNUP_TIMESTAMP'] : $SIGNUP_TIMESTAMP : $_SIGNUP_TIMESTAMP;
    $_PUBLIC_KEY = is_null($_PUBLIC_KEY) ? array_key_exists('PUBLIC_KEY', $_REQUEST) ? $_REQUEST['PUBLIC_KEY'] : $PUBLIC_KEY : $_PUBLIC_KEY;
    $_VERSION = is_null($_VERSION) ? array_key_exists('VERSION', $_REQUEST) ? $_REQUEST['VERSION'] : $VERSION : $_VERSION;
    $_REFERRAL_IP = is_null($_REFERRAL_IP) ? array_key_exists('REFERRAL_IP', $_REQUEST) ? $_REQUEST['REFERRAL_IP'] : $REFERRAL_IP : $_REFERRAL_IP;
    $_REFERRER = is_null($_REFERRER) ? array_key_exists('REFERRER', $_REQUEST) ? $_REQUEST['REFERRER'] : $REFERRER : $_REFERRER;
    $_API_KEY = is_null($_API_KEY) ? array_key_exists('API_KEY', $_REQUEST) ? $_REQUEST['API_KEY'] : $API_KEY : $_API_KEY;

    // TODO default to ticker from quote
    $_CRYPTO_TICKER = is_null($_CRYPTO_TICKER) ? array_key_exists('CRYPTO_TICKER', $_REQUEST) ? $_REQUEST['CRYPTO_TICKER'] : $CRYPTO_TICKER : $_CRYPTO_TICKER;

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
    try {
        $result = file_get_contents($url, false, $context);
    } catch (Exception $e) {
        // $error = true;
        $result = json_encode(array('error' => 'true', 'message' => $e->getMessage()));
    }
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
