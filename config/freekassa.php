<?php

    return [

        /*
         |--------------------------------------------------------------------------
         | Merchant params
         |--------------------------------------------------------------------------
         |
         |
         */

        'merchant_id' => '',
        'secret' => '',
        'secret2' => '',

        /*
         |--------------------------------------------------------------------------
         | Wallet params
         |--------------------------------------------------------------------------
         |
         |
         */

        'wallet_id' => '',
        'api_key' => '',

        'default_lang' => 'ru',
        'default_currency' => '133',
        'cashout_currencies' =>[
            'FK_WALLET_RUB' => '133',
            'QIWI' => '63',
            'QIWI_EURO' => '161',
            'QIWI_USD' => '123',
            'YANDEX_MONEY' => '45',
            'QIWI_KZT' => '162',
            'VISA_MASTERCARD_RUB' => '94',
            'OOOPAY_RUR' => '106',
            'OOOPAY_USD' => '87',
            'OOOPAY_EUR' => '109',
            'WEBMONEY_WMR' => '1',
            'WEBMONEY_WMZ' => '2',
            'PAYEER_RUB' => '114',
            'PERFECT_MONEY_USD' => '64',
            'PERFECT_MONEY_EUR' => '69',
            'MEGAFON_MOBILE' => '82',
            'MTS_MOBILE' => '84',
            'TELE2_MOBILE' => '132',
            'BEELINE_MOBILE' => '83',
            'VISA_MASTERCARD_INT' => '158',
            'VISA_UAH_CASHOUT' => 157,
            'PAYPAL' => '70',
            'ADVCASH_USD' => '136',
            'ADVCASH_RUB' =>  '150'
        ],
        'crypto_currencies' => [
            'btc',
            'ltc',
            'eth'
        ]
    ];
