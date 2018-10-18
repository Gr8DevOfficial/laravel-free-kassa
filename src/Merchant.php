<?php

namespace Gr8devofficial\LaravelFreecassa;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use SimpleXMLElement;

class Merchant
{
    const BASE_URL = 'http://www.free-kassa.ru/api.php';

    protected $paymentCurrencies = [
        'ooopay',
        'yandex',
        'qiwi',
        'card',
        'wmr',
        'wmz',
        'fkw',
        'cardint'
    ];

    /**
     *
     * @var string
     */
    protected $merchantId;

    public function __construct()
    {
        $this->config = Config::get('freekassa');
        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'timeout'  => 10,
            'connect_timeout' => 10
        ]);
        $this->merchantId = $this->config['merchant_id'];
    }

    /**
     *
     * @param  array $data
     * @return mixed SimpleXMLElement or string
     */
    protected function get($data)
    {
        $data = array_merge($data, [
            'merchant_id' => $this->merchantId,
            's' => $this->makeSign()
        ]);
        try {
            $result = $this->client->get(null, [
                'query' => $data
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return new SimpleXMLElement((string)$result->getBody());
    }

    /**
     * change merchant ID
     * @param string $value
     * @return Wallet
     */
    public function setMerchantId(string $value)
    {
        $this->merchantId = $value;
        return this;
    }

    /**
     * get merchant ID
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     *
     * @return mixed SimpleXMLElement or string
     */
    public function getBalance()
    {
        $data = [
            'action' => 'get_balance',
        ];
        return $this->get($data);
    }

    /**
     *
     * @param  mixed $orderId integer or null
     * @param  mixed $intid string or null
     * @return mixed SimpleXMLElement or false
     */
    public function checkOrderStatus($orderId = null, $intid = null)
    {
        $data = [
            'action' => 'check_order_status',
        ];
        if ($orderId) {
            $data['order_id'] = $orderId;
        } elseif ($intid) {
            $data['intid'] = $intid;
        } else {
            throw new Exception('order_id or intid must be filled');
        }
        return $this->get($data);
    }

    /**
     *
     * @param  string $currency
     * @param  float $intid
     * @return mixed SimpleXMLElement or string
     */
    public function payment($currency, $amount)
    {
        if(!in_array($currency, $this->paymentCurrencies)){
            throw new Exception('Currency not found');
        }
        $data = [
            'currency' => $currency,
            'amount' => $amount,
            'action' => 'payment',
        ];
        return $this->get($data);
    }

    protected function makeSign()
    {
        return md5($this->merchantId.$this->config['secret2']);
    }
}
