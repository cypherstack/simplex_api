<?php

// Set these values in config.php, which is included and overwrites these below
// -----------------------------------------------------------------------------
$apiKey = 'API Key';
$publicKey = 'Public Key';
$wallet_id = 'Wallet ID';
$referrer = 'https://example.com/simplex';
$referral_ip = '8.8.8.8';
// -----------------------------------------------------------------------------
include 'config.php';

// TODO get all of the below from parameters/wallet
$crypto_ticker = 'BTC';
$crypto_amount = 0.00411956;
$fiat_ticker = 'USD';
$address = 'bc1qar0srrr7xfkvy5l643lydnw9re59gtzzwf5mdq';
$version = '0.0.1'; 
$app_end_user_id = guidv4();
$signup_timestamp = '1994-11-05T08:15:30-05:00';

$quote_id = 'e5440c77-b669-48d5-bc5c-d2f7417434bb'; // Set later in get_quote()

// TODO save/return all of the below
$payment_id = guidv4();
$order_id = guidv4();

// Get quote
// -----------------------------------------------------------------------------
get_quote();

// Place order
// -----------------------------------------------------------------------------
place_order();

function get_quote() {
    // Getting a quote:
    // curl --request POST \
    //      --url https://sandbox.test-simplexcc.com/wallet/merchant/v2/quote \
    //      --header 'Authorization: ApiKey $apiKey' \
    //      --header 'accept: application/json' \
    //      --header 'content-type: application/json' \
    //      -d '{"end_user_id": "9e4ba9c9-5a06-4a1e-8e1c-ad096b31543d", "digital_currency": "BTC", "fiat_currency": "USD", "requested_currency": "BTC", "requested_amount": 0.00411956, "wallet_id": "stackwalet", "client_ip": "207.66.86.226"}'

    global $app_end_user_id, $crypto_ticker, $fiat_ticker, $crypto_ticker, $crypto_amount, $wallet_id, $referral_ip, $apiKey, $quote_id;
    $url = 'https://sandbox.test-simplexcc.com/wallet/merchant/v2/quote';
    $data = array(
        'end_user_id' => $app_end_user_id,
        'digital_currency' => $crypto_ticker,
        'fiat_currency' => $fiat_ticker,
        'requested_currency' => $crypto_ticker,
        'requested_amount' => $crypto_amount,
        'wallet_id' => $wallet_id,
        'client_ip' => $referral_ip,
    );
    $options = array(
        'http' => array(
            'method'  => 'POST',
            'header'  => "Authorization: ApiKey $apiKey\r\nContent-type: application/json\r\nAccept: application/json\r\n",
            'content' => json_encode($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) {
        echo 'error';
    } else {
        $json_result = json_decode($result);
        $quote_id = $json_result->quote_id;
        // Debugging
        var_dump($result);
        echo "<br><br>";
        echo "<br><br>";
    }
}

function place_order() {
    // Placing an order:
    // curl --request POST \
    //      --url https://sandbox.test-simplexcc.com/wallet/merchant/v2/payments/partner/data \
    //      --header 'Authorization: ApiKey $apiKey' \
    //      --header 'accept: application/json' \
    //      --header 'content-type: application/json' \
    //      -d '{"account_details": {"app_provider_id": "$publicKey", "app_version_id": "123", "app_end_user_id": "01e7a0b9-8dfc-4988-a28d-84a34e5f0a63", "signup_login": {"timestamp": "1994-11-05T08:15:30-05:00", "ip": "207.66.86.226"}}, "transaction_details": {"payment_details": {"quote_id": "3b58f4b4-ed6f-447c-b96a-ffe97d7b6803", "payment_id": "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa", "order_id": "789", "original_http_ref_url": "https://stackwallet.com/simplex", "destination_wallet": {"currency": "BTC", "address": "bc1qar0srrr7xfkvy5l643lydnw9re59gtzzwf5mdq"}}}}'

    global $publicKey, $version, $app_end_user_id, $signup_timestamp, $referral_ip, $quote_id, $payment_id, $order_id, $referrer, $crypto_ticker, $address, $apiKey;
    $url = 'https://sandbox.test-simplexcc.com/wallet/merchant/v2/payments/partner/data';
    $data = array(
        'account_details' => array(
            'app_provider_id' => $publicKey,
            'app_version_id' => $version,
            'app_end_user_id' => $app_end_user_id,
            'signup_login' => array(
                'timestamp' => $signup_timestamp,
                'ip' => $referral_ip,
            )
        ),
        'transaction_details' => array(
            'payment_details' => array(
                'quote_id' => $quote_id,
                'payment_id' => $payment_id,
                'order_id' => $order_id,
                'original_http_ref_url' => $referrer,
                'destination_wallet' => array(
                    'currency' => $crypto_ticker,
                    'address' => $address,
                )
            )
        )
    );
    var_dump($data);
    $options = array(
        'http' => array(
            'method'  => 'POST',
            'header'  => "Authorization: ApiKey $apiKey\r\nContent-type: application/json\r\nAccept: application/json\r\n",
            'content' => json_encode($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) {
        echo 'error';
    } else {
        // Debugging
        var_dump($result);
        echo "<br><br>";
    }
}

function guidv4() { // See https://stackoverflow.com/a/15875555
    $data = openssl_random_pseudo_bytes(16);

    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

?>
