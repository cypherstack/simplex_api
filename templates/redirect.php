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
    <body>
        <form id="payment_form" action="https://sandbox.test-simplexcc.com/payments/new" method="post">
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
    </body>
</html>
EOD;
    return $html;
}

?>
