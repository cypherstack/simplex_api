<?php

// TODO style

function redirect_template($_PAYMENT_ID, $_RETURN_URL_SUCCESS, $_RETURN_URL_FAILURE, $_WALLET_ID) {
    global $PAYMENT_ID, $RETURN_URL_SUCCESS, $RETURN_URL_FAILURE, $WALLET_ID;
    $_PAYMENT_ID = isset($_REQUEST['PAYMENT_ID']) ? $_REQUEST['PAYMENT_ID'] : $PAYMENT_ID;
    $_RETURN_URL_SUCCESS = isset($_REQUEST['RETURN_URL_SUCCESS']) ? $_REQUEST['RETURN_URL_SUCCESS'] : $RETURN_URL_SUCCESS;
    $_RETURN_URL_FAILURE = isset($_REQUEST['RETURN_URL_FAILURE']) ? $_REQUEST['RETURN_URL_FAILURE'] : $RETURN_URL_FAILURE;
    $_WALLET_ID = isset($_REQUEST['WALLET_ID']) ? $_REQUEST['WALLET_ID'] : $WALLET_ID;
    // TODO sanitize $_REQUEST inputs above

    $html = <<<EOD
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Redirecting to Simplex</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
        <style>
            html, body, div, main, h1 { margin: 0;  padding: 0; border: 0; font-size: 100%; font: inherit;  vertical-align: baseline;}
            h1 {font-family: 'Roboto', sans-serif;}
            body {width: 100%; height: 100vh; background-color: #fefefe;}
            main {height: 100%; width: 100%; position: relative;}
            main .wrap {position: absolute; top: 50%;  left: 50%;  transform: translate(-50%, -50%); text-align: center;}
            main .wrap h1 {font-size: 1.5rem; text-align: center; color: #232323;}
        </style>
    </head>
    <body>
        <form id="payment_form" action="$CHECKOUT_ROOT/payments/new" method="post">
            <input type="hidden" name="version" value="1">
            <input type="hidden" name="partner" value="$_WALLET_ID">
            <input type="hidden" name="payment_flow_type" value="wallet">
            <input type="hidden" name="return_url_success" value="$_RETURN_URL_SUCCESS">
            <input type="hidden" name="return_url_fail" value="$_RETURN_URL_FAILURE">
            <input type="hidden" name="payment_id" value="$_PAYMENT_ID">
        </form>

        <script type="text/javascript">
            document.forms["payment_form"].submit();
        </script>
        
        <main>
            <div class="wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 170.08 170.08"><defs><style>.c{fill:#b3b3b3;}.d{fill:#666;}</style></defs><g id="a"/><g id="b"><g><path class="c" d="M97.95,31.81c-8.25-4.76-18.56,1.2-18.56,10.72s4.6,17.77,12.14,22.86l31.93,18.51,.08-.03,32.36-18.7L97.95,31.81Z"/><path class="d" d="M91.53,65.39c-7.54-5.09-12.14-13.66-12.14-22.86,0-4.53,2.37-8.31,5.74-10.45-.16,.09,.16-.09,0,0l-50.51,28.38-8.11,4.9-5.53,3.18c3.48-1.77,7.84-1.91,11.73,.35l13.91,7.92,12.19,7.05,32.36,18.67,32.28-18.65-31.93-18.51Z"/><path d="M155.91,102.55l-64.73,37.4-12.22-7.05-.05-.05-52.45-30.3c-3.59-2.34-6.51-5.53-8.57-9.12-2.4-4.14-3.7-8.9-3.7-13.83,0-4.44,2.23-8.11,5.47-10.29,.44-.27,.87-.52,1.33-.76,3.48-1.77,7.84-1.91,11.73,.35l13.83,7.98,12.19,7.02,.08-.03,32.36,18.67,32.28-18.65,32.45,18.65Z"/></g></g></svg>
                <h1>Redirecting to Simplex</h1>  
            </div>
        </main>
    </body>
</html>
EOD;
    return $html;
}

?>
