<?php

namespace Gr8devofficial\LaravelFreecassa;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

class Wallet
{
    const BASE_URL = 'https://www.fkwallet.ru/api_v1.php';

    /**
     *
     * @var string
     */
    protected $walletId;
    protected $client;
    protected $config;

    public function __construct()
    {
        $this->config = Config::get('freekassa');
        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'timeout'  => 10,
            'connect_timeout' => 10
        ]);
        $this->walletId = $this->config['wallet_id'];
    }

    /**
     * Make POST request with given data to API
     * @param  array $data      Request parameters array
     * @return Collection       Request result
     */
    protected function post($data)
    {
        try {
            $result = $this->client->request('POST', self::BASE_URL, ['form_params'=>$data]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return collect(json_decode($result->getBody()->getContents()));
    }

    /**
     * change wallet ID
     * @param string $value
     * @return Wallet
     */
    public function setWalletId(string $value)
    {
        $this->walletId = $value;
        return this;
    }

    /**
     * get Wallet ID
     * @return string
     */
    public function getWalletId()
    {
        return $this->walletId;
    }

    /**
     * Make online payment to some service
     * @param  integer $serviceId       Id of service. Get it from providers() method.
     * @param  string $account          Id of account in service
     * @param  float $amount            Payment amount
     * @return Collection
     */
    public function onlinePayment(integer $serviceId, string $account, float $amount)
    {
        $data = [
            'wallet_id' => $this->walletId,
            'service_id' => $serviceId,
            'account' => $account,
            'amount' => $amount,
            'sign' => md5($this->walletId.$account.$amount.$this->config['api_key']),
            'action' => 'online_payment',
        ];
        return $this->post($data);
    }

    /**
     * Get status for chashout payment
     * @param  integer $paymentId       Id of cashout payment
     * @return Collection
     */
    public function getPaymentStatus(integer $paymentId)
    {
        $data = [
            'wallet_id' => $this->walletId,
            'payment_id' => $paymentId,
            'sign' => md5($this->walletId.$paymentId.$this->config['api_key']),
            'action' => 'get_payment_status',
        ];
        return $this->post($data);
    }

    /**
     * Get status of online payment
     * @param  integer $paymentId       Id of payment made by onlinePayment() method
     * @return Collection
     */
    public function checkOnlinePayment(integer $paymentId)
    {
        $data = [
            'wallet_id' => $this->walletId,
            'payment_id' => $paymentId,
            'sign' => md5($this->walletId.$paymentId.$this->config['api_key']),
            'action' => 'check_online_payment',
        ];
        return $this->post($data);
    }

    /**
     * Get service providers list avaliable for online payment
     * @return Collection
     */
    public function providers()
    {
        $data = [
            'wallet_id' => $this->walletId,
            'sign' => md5($this->walletId.$this->config['api_key']),
            'action' => 'providers',
        ];
        return $this->post($data);
    }

    /**
     * Transfer to other Freekassa wallets
     * @param  string $purse        Id of Freekassa wallet for transfer
     * @param  float  $amount       Amount to transfer
     * @return Collection
     */
    public function transfer(string $purse, float $amount)
    {
        $data = [
            'wallet_id' => $this->walletId,
            'purse' => $purse,
            'sign' => md5($this->walletId.$amount.$purse.$this->config['api_key']),
            'action' => 'transfer',
        ];
        return $this->post($data);
    }

    /**
     * Get account's wallets balance
     * @return Collection
     */
    public function getBalance()
    {
        $data = [
            'wallet_id' => $this->walletId,
            'sign' => md5($this->walletId.$this->config['api_key']),
            'action' => 'get_balance',
        ];
        return $this->post($data);
    }

    /**
     * Cashout to differerent pay systems
     * @param  string  $currency         String key for payment system. See freekassa.php config file.
     * @param  string  $purse            Destination wallet id
     * @param  decimal $amount           Cashout amount
     * @param  string  $desc             Optional description
     * @param  integer $disable_exchange Disable automatic exchange
     * @return Collection
     */
    public function cashout(string $currency, string $purse, float $amount, string $desc = null, boolean $disable_exchange = null)
    {
        if (!isset($this->config['cashout_currencies'][$currency])) {
            throw new \Exception('Incorrect currency string key!');
        } else {
            $currencyCode = $this->config['cashout_currencies'][$currency];
        }
        $data = [
            'wallet_id' => $this->walletId,
            'purse' => $purse,
            'amount' => $amount,
            'desc' => $desc ?: '',
            'disable_exchange' => $disable_exchange ?: 0,
            'currency' => $currencyCode,
            'sign' => md5($this->walletId.$currencyCode.$amount.$purse.$this->config['api_key']),
            'action' => 'cashout',
        ];
        return $this->post($data);
    }

    /**
     * Create Crypto currency address
     * @param  string $currency  Crypto currency type. See avaliable types in freekassa.php config file
     * @return Collection
     */
    public function createCryptoAddress(string $currency)
    {
        $currencies = $this->config['crypto_currencies'];
        if (!in_array($currency, $currencies)) {
            throw new \Exception('Incorrect crypto currency!');
        }
        $data = [
            'wallet_id' => $this->walletId,
            'sign' => md5($this->walletId.$this->config['api_key']),
            'action' => 'create_'.$currency.'_address',
        ];
        return $this->post($data);
    }

    /**
     * Get Crypto currency address
     * @param  string $currency  Crypto currency type. See avaliable types in freekassa.php config file
     * @return Collection
     */
    public function getCryptoAddress(string $currency)
    {
        $currencies = $this->config['crypto_currencies'];
        if (!in_array($currency, $currencies)) {
            throw new \Exception('Incorrect crypto currency!');
        }
        $data = [
            'wallet_id' => $this->walletId,
            'sign' => md5($this->walletId.$this->config['api_key']),
            'action' => 'get_'.$currency.'_address',
        ];
        return $this->post($data);
    }

    /**
     * Get Crypto currency transaction info
     * @param  string $currency         Crypto currency type. See avaliable types in freekassa.php config file
     * @param  string $transactionId    Crypto currency transaction ID
     * @return Collection
     */
    public function getCryptoInfo(string $currency, string $transactionId)
    {
        $currencies = $this->config['crypto_currencies'];
        if (!in_array($currency, $currencies)) {
            throw new \Exception('Incorrect crypto currency!');
        }
        $data = [
            'wallet_id' => $this->walletId,
            'transaction_id' => $transactionId,
            'sign' => md5($this->walletId.$transactionId.$this->config['api_key']),
            'action' => 'get_'.$currency.'_transaction',
        ];
        return $this->post($data);
    }

}
