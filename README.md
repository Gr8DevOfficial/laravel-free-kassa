# laravel-free-kassa
[![Latest Stable Version](https://poser.pugx.org/gr8devofficial/laravel-free-kassa/v/stable)](https://packagist.org/packages/gr8devofficial/laravel-free-kassa)
[![Total Downloads](https://poser.pugx.org/gr8devofficial/laravel-free-kassa/downloads)](https://packagist.org/packages/gr8devofficial/laravel-free-kassa)

[English doc](https://github.com/Gr8DevOfficial/laravel-free-kassa/blob/master/READMEENG.md)

Пакет для работы с сервисом free-kassa.ru. Протестировано с Laravel 5.5 и PHP 7.1.

Возможности:
- Прием плетежей
- Данные о балансе кассы
- Вывод средств
- Выплаты с кошелька
- Получение статуса выплаты
- Получение списка доступных для онлайн оплаты операторов
- Онлайн оплата
- Получение статуса онлайн оплаты
- Создание/получение данных адреса криптовалютного кошелька
- Получение статуса криптовалютной транзакции

## Установка
Установите пакет через composer.
```shell
composer require gr8devofficial/laravel-free-kassa
```
Публикация конфига.
```shell
php artisan vendor:publish --provider=Gr8devofficial\LaravelFreecassa\ServiceProvider
```

## Использование

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

Список доступных для вывода средств систем в [free-kassa API doc](http://www.free-kassa.ru/docs/api.php#api_payment)

Так же можно переопределить id кассы на лету:
```php
use Gr8devofficial\LaravelFreecassa\Merchant;

$balance = (new Merchant)->setMerchantId('123456')->getBalance();
```

Получение баланса кошелька:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;

$balance = (new Wallet)->getBalance();
```

Выплата средств из кошелька:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;
//$currency текстовый ключ способа выплаты. Список доступных ключей см. в конфиг файле freekassa.php
//$purse идентификатор получателя в системе куда производится выплата. Напр. номер телефона.
//$amount Сумма
//$desc Необязательное примечание
//$disable_exchange Если требуется отключить автоматический обмен валют, передать 1
$response = (new Wallet)->cashout($currency, $purse, $amount, $desc, $disable_exchange);

//$response будет содержать объект ответа от сервиса. Данные об операции в случае успеха или данные об ошибке.
```
Получение статуса операции выплаты из кошелька:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;
//$paymentId ID выплаты, возвращенный сервисом в методе cashout()
$response = (new Wallet)->getPaymentStatus($paymentId);

//$response будет содержать объект ответа от сервиса. Данные об операции в случае успеха или данные об ошибке.
```

Перевод на другой кошелек free-kassa:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;
//$purse ID кошелька получателя
//$amount Сумма перевода
$response = (new Wallet)->transfer($purse, $amount);

//$response будет содержать объект ответа от сервиса. Данные об операции в случае успеха или данные об ошибке.
```

Оплата онлайн услуг:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;
//$serviceId ID сервиса для оплаты. Список доступных возвращается методом providers()
//$account ID получателя напр. номер телефона при оплате услуг связи
//$amount Сумма перевода
$response = (new Wallet)->onlinePayment($serviceId, $account, $amount);

//$response будет содержать объект ответа от сервиса. Данные об операции в случае успеха или данные об ошибке.
```
Список доступных для оплаты услуг:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;

$response = (new Wallet)->providers();

//$response будет содержать объект ответа от сервиса. Данные об операции в случае успеха или данные об ошибке.
```
Проверка статуса онлайн платежа:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;
//$paymentId ID оплаты, полученый в ответе сервиса на метод onlinePayment()
$response = (new Wallet)->checkOnlinePayment($paymentId);

//$response будет содержать объект ответа от сервиса. Данные об операции в случае успеха или данные об ошибке.
```

Создание криптовалютного адреса:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;
//$currency вид криптовалюты. Доступные виды валют см. в конфиге freekassa.php
$response = (new Wallet)->createCryptoAddress($currency);

//$response будет содержать объект ответа от сервиса. Данные об операции в случае успеха или данные об ошибке.
```

Получение криптовалютного адреса:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;
//$currency вид криптовалюты. Доступные виды валют см. в конфиге freekassa.php
$response = (new Wallet)->getCryptoAddress($currency);

//$response будет содержать объект ответа от сервиса. Данные об операции в случае успеха или данные об ошибке.
```

Получение информации о криптовалютной операции:
```php
use Gr8devofficial\LaravelFreecassa\Wallet;
//$currency Вид криптовалюты. Доступные виды валют см. в конфиге freekassa.php
//$transactionId ID транзакции
$response = (new Wallet)->getCryptoInfo($currency, $transactionId);

//$response будет содержать объект ответа от сервиса. Данные об операции в случае успеха или данные об ошибке.
```
## Лицензия
MIT

## Отказ от гарантий
Обратите внимание: все инструменты / скрипты в этом репозитории выпущены для использования «КАК ЕСТЬ» без каких-либо гарантий любого рода, включая, но не ограничиваясь, их установку, использование или производительность. Мы отказываемся от каких-либо гарантий, явных или подразумеваемых, включая, но не ограничиваясь, любую гарантию неинформирования, товарной пригодности и / или пригодности для определенной цели. Мы не гарантируем, что технология удовлетворит ваши требования, что ее работа будет бесперебойной или безошибочной или что любые ошибки будут исправлены.

Любое использование этих скриптов и инструментов на свой страх и риск. Нет гарантии, что они прошли тщательное тестирование в сопоставимой среде, и мы не несем ответственности за любой ущерб или потерю данных, понесенные при их использовании.

Вы несете ответственность за просмотр и тестирование любых скриптов, которые вы выполняете, прежде чем использовать их в любой среде без тестирования.

Спасибо,
Команда Gr8Dev.
