# simplex_api
A Simple Simplex API

## Usage

See `simplex_api.php` for parameter and return types for the following endpoints:

 - `api.php/supported_cryptos` GET 
 - `api.php/supported_fiats` GET
 - `api.php/quote` POST or GET
 - `api.php/order` POST or GET
 - `api.php/checkout` POST or GET

Test with `curl` after configuring `config.php` with sandbox or production keys from Simplex like:
```
# simplex-api/quote

curl -H "Content-Type: application/json" -d '{"digital_currency": "BTC", "fiat_currency": "USD", "requested_currency": "USD", "requested_amount": 100}' http://localhost/quote

# simplex-api/order

curl -H "Content-Type: application/json" -d '{"account_details": {"app_end_user_id": "2d766b5c-103b-46f7-a943-64ed9ea5ce7a"}, "transaction_details": {"payment_details": {"fiat_total_amount": {"currency": "USD", "amount": 100}, "requested_digital_amount": {"currency": "BTC", "amount": 0.00411831}, "destination_wallet": {"currency": "BTC", "address": "3J98t1WpEZ73CNmQviecrnyiWrnqRhWNLy"}}}}' http://localhost/order
```

You should generate a UUIDv4 `app_end_user_id`

See [Stack Wallet](https://github.com/cypherstack/stack_wallet/blob/staging/lib/services/buy/simplex/simplex_api.dart) for an example usage in Dart

## Configuration
Edit all values set in `config.php` accordingly

Use `git update-index --skip-worktree config.php` to ignore updates to the configuration
