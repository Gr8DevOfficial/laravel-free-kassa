<?php

namespace Gr8devofficial\LaravelFreecassa;

class Wallet
{

    const BASE_URL = 'https://www.fkwallet.ru/api_v1.php';

    /**
     *
     * @var string
     */
    protected $walletId;

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

    protected function post($data){
        try {
            $result = $this->client->post('api_v1.php', $data);
        } catch (\Exception $e) {
            return null;
        }
        return json_decode($result->getBody());
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
     *
     * @param  integer $serviceId
     * @param  string $account
     * @param  float $amount
     * @return mixed            Payment ID or null
     */
    public function onlinePayment($serviceId, $account, $amount){
        $data = [
            'wallet_id' => $this->walletId,
            'service_id' => $serviceId,
            'account' => $account,
            'amount' => $amount,
            'sign' => md5($this->walletId.$account.$amount.$this->config['api_key']),
            'action' => 'online_payment',
        ];
        $result = $this->post($data);
    }

    /**
     * get status for payment
     * @param  integer $paymentId
     * @return Collection
     */
    public function getPaymentStatus($paymentId)
    {
        $data = [
            'wallet_id' => $this->walletId,
            'payment_id' => $paymentId,
            'sign' => md5($this->walletId.$paymentId.$this->config['api_key']),
            'action' => 'get_payment_status',
        ];
        $result = $this->post($data);
    }

    /**
     *
     * @param  integer $paymentId
     * @return Collection
     */
    public function checkOnlinePayment($paymentId)
    {
        $data = [
            'wallet_id' => $this->walletId,
            'payment_id' => $paymentId,
            'sign' => md5($this->walletId.$paymentId.$this->config['api_key']),
            'action' => 'check_online_payment',
        ];
        $result = $this->post($data);
    }

    /**
     * get providers list
     * @return Collection
     */
    public function providers()
    {
        $data = [
            'wallet_id' => $this->walletId,
            'sign' => md5($this->walletId.$this->config['api_key']),
            'action' => 'providers',
        ];
        $result = $this->post($data);
    }

    /**
     * get providers list
     * @return Collection
     */
    public function transfer($purse, $amount)
    {
        $data = [
            'wallet_id' => $this->walletId,
            'purse' => $purse,
            'sign' => md5($this->walletId.$amount.$purse.$this->config['api_key']),
            'action' => 'transfer',
        ];
        $result = $this->post($data);
    }

    /**
     * get providers list
     * @return Collection
     */
    public function getBalance()
    {
        $data = [
            'wallet_id' => $this->walletId,
            'sign' => md5($this->walletId.$this->config['api_key']),
            'action' => 'get_balance',
        ];
        $result = $this->post($data);
    }

}
