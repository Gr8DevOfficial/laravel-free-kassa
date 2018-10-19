# laravel-free-kassa
[![Latest Stable Version](https://poser.pugx.org/gr8devofficial/laravel-free-kassa/v/stable)](https://packagist.org/packages/gr8devofficial/laravel-free-kassa)
[![Total Downloads](https://poser.pugx.org/gr8devofficial/laravel-free-kassa/downloads)](https://packagist.org/packages/gr8devofficial/laravel-free-kassa)
## Установка / Installation
Установите пакет через composer. / Require this package with composer.
```shell
composer require gr8devofficial/laravel-free-kassa
```
Публикация конфига.
```shell
php artisan vendor:publish --provider=Gr8devofficial\LaravelFreecassa\ServiceProvider
```

## Использование / Usage

Метод получения баланса кассы:
```php
use Gr8devofficial\LaravelFreecassa\Merchant;

$balance = (new Merchant)->getBalance();
```
Метод для получения статуса заказа:
```php
use Gr8devofficial\LaravelFreecassa\Merchant;

$status = (new Merchant)->checkOrderStatus($orderId);
//Или используя intid
$status = (new Merchant)->checkOrderStatus(null, $intid);
```
Метод для вывода средств:
```php
use Gr8devofficial\LaravelFreecassa\Merchant;

$result = (new Merchant)->payment('fkw', 1000);
```
Так же можно переопределить id кассы на лету:
```php
use Gr8devofficial\LaravelFreecassa\Merchant;

$balance = (new Merchant)->setMerchantId('123456')->getBalance();
```
