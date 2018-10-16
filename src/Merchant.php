<?php

namespace Gr8devofficial\LaravelFreecassa;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use SimpleXMLElement;

class Merchant
{

    const BASE_URL = 'http://www.free-kassa.ru/api.php';

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
     * @return SimpleXMLElement
     */
    protected function get($data){
        try {
            $result = $this->client->get(null, [
                'query' => $data
            ]);
        } catch (\Exception $e) {
            return null;
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
     * @return SimpleXMLElement
     */
    public function getBalance(){
        $data = [
            'merchant_id' => $this->merchantId,
            's' => md5($this->merchantId.$this->config['secret2']),
            'action' => 'get_balance',
        ];
        return $this->get($data);
    }

}
