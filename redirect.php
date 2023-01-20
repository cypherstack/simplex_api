<html>
<body>
<form id="payment_form" action="https://sandbox.test-simplexcc.com/payments/new" method="post">
<input type="hidden" name="version" value="1">
<input type="hidden" name="partner" value="stackwallet">
<input type="hidden" name="payment_flow_type" value="wallet">
<input type="hidden" name="return_url_success" value="https://www.partner.com">
<input type="hidden" name="return_url_fail" value="https://www.partner.com/support">
<input type="hidden" name="payment_id" value="baaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa">
</form>

<script type="text/javascript">
document.forms["payment_form"].submit();
</script>

</body>
</html>
