<?php

// TODO style

function redirect_template($_PAYMENT_ID, $_RETURN_URL_SUCCESS, $_RETURN_URL_FAIL, $_WALLET_ID) {
    $html = <<<EOD
<html>
    <body>
        <form id="payment_form" action="https://sandbox.test-simplexcc.com/payments/new" method="post">
            <input type="hidden" name="version" value="1">
            <input type="hidden" name="partner" value="$_WALLET_ID">
            <input type="hidden" name="payment_flow_type" value="wallet">
            <input type="hidden" name="return_url_success" value="$_RETURN_URL_SUCCESS">
            <input type="hidden" name="return_url_fail" value="$_RETURN_URL_FAIL">
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
