# laravel-free-kassa
[![Latest Stable Version](https://poser.pugx.org/gr8devofficial/laravel-free-kassa/v/stable)](https://packagist.org/packages/gr8devofficial/laravel-free-kassa)
[![Total Downloads](https://poser.pugx.org/gr8devofficial/laravel-free-kassa/downloads)](https://packagist.org/packages/gr8devofficial/laravel-free-kassa)

[Документация на русском](https://github.com/Gr8DevOfficial/laravel-free-kassa/blob/master/README.md)

This package works with free-kassa.ru API. Tested with Laravel 5.5 and PHP 7.1.

Features:
- Accept online payments
- Merchant balance info
- Withdrawal of funds
- Payments with Wallet
- Wallet payment status info
- Get list of avaliable online services providers
- Online services payments
- Online services payment info
- Create/Get crypto currency address
- Get crypto currency operation info

## Installation
Install this package with composer.
```shell
composer require gr8devofficial/laravel-free-kassa
```
Publish config file.
```shell
php artisan vendor:publish --provider=Gr8devofficial\LaravelFreecassa\ServiceProvider
```

## Usage

Get merchant balance:
```php
use Gr8devofficial\LaravelFreecassa\Merchant;

$balance = (new Merchant)->getBalance();
```
Get order info:
```php
use Gr8devofficial\LaravelFreecassa\Merchant;

$status = (new Merchant)->checkOrderStatus($orderId);
//Or use intid
$status = (new Merchant)->checkOrderStatus(null, $intid);
```
Withdrawal of funds:
```php
use Gr8devofficial\LaravelFreecassa\Merchant;

$result = (new Merchant)->payment('fkw', 1000);
```
List of avaliable currencies to withdraw is in [free-kassa API doc](http://www.free-kassa.ru/docs/api.php#api_payment)

Also you can change merchant id on the fly:
```php
use Gr8devofficial\LaravelFreecassa\Merchant;

$balance = (new Merchant)->setMerchantId('123456')->getBalance();
```

Get wallet balance:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;

$balance = (new Wallet)->getBalance();
```

Funds witdraw from wallet:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;
//$currency String key of withdrowal type. See avaliable in config file freekassa.php
//$purse Recipient Id. Phone number for example.
//$amount Amount of withdraw
//$desc Optional description
//$disable_exchange Set to 1 if you want to disable automatic currency exchange
$response = (new Wallet)->cashout($currency, $purse, $amount, $desc, $disable_exchange);

//$response will contain object of service response. Operation details or error details.
```
Get withdrow operation info:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;
//$paymentId ID of operation got from cashout()
$response = (new Wallet)->getPaymentStatus($paymentId);

//$response will contain object of service response. Operation details or error details.
```

Transfer to other free-kassa wallet:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;
//$purse ID of recipient wallet
//$amount Transfer amount
$response = (new Wallet)->transfer($purse, $amount);

//$response will contain object of service response. Operation details or error details.
```

Online service payment:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;
//$serviceId ID of service. Get list of avaliable services with providers()
//$account ID of reciever for example phone number
//$amount Amount of payment
$response = (new Wallet)->onlinePayment($serviceId, $account, $amount);

//$response will contain object of service response. Operation details or error details.
```
Get avaliable services for online payment:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;

$response = (new Wallet)->providers();

//$response will contain object of service response. Operation details or error details.
```
Get online payment status:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;
//$paymentId ID of operation got from onlinePayment()
$response = (new Wallet)->checkOnlinePayment($paymentId);

//$response will contain object of service response. Operation details or error details.
```

Create crypto currency address:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;
//$currency Currency type. See avaliable in config freekassa.php
$response = (new Wallet)->createCryptoAddress($currency);

//$response will contain object of service response. Operation details or error details.
```

Get crypto currency address:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;
//$currency Currency type. See avaliable in config freekassa.php
$response = (new Wallet)->getCryptoAddress($currency);

//$response will contain object of service response. Operation details or error details.
```

Get crypto currency operation status:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;
//$currency Currency type. See avaliable in config freekassa.php
//$transactionId ID of operation
$response = (new Wallet)->getCryptoInfo($currency, $transactionId);

//$response will contain object of service response. Operation details or error details.
```
## License

MIT

## DISCLAIMER
Please note: all tools/ scripts in this repo are released for use "AS IS" without any warranties of any kind, including, but not limited to their installation, use, or performance. We disclaim any and all warranties, either express or implied, including but not limited to any warranty of noninfringement, merchantability, and/ or fitness for a particular purpose. We do not warrant that the technology will meet your requirements, that the operation thereof will be uninterrupted or error-free, or that any errors will be corrected.

Any use of these scripts and tools is at your own risk. There is no guarantee that they have been through thorough testing in a comparable environment and we are not responsible for any damage or data loss incurred with their use.

You are responsible for reviewing and testing any scripts you run thoroughly before use in any non-testing environment.

Thanks,
Gr8Dev team.
