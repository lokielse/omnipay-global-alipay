<?php

namespace Omnipay\GlobalAlipay;

use Omnipay\GlobalAlipay\Message\AppPurchaseResponse;
use Omnipay\GlobalAlipay\Message\CompletePurchaseResponse;
use Omnipay\Omnipay;
use Omnipay\Tests\GatewayTestCase;

class AppGatewayTest extends GatewayTestCase
{

    /**
     * @var AppGateway $gateway
     */
    protected $gateway;

    protected $options;


    public function setUp()
    {
        parent::setUp();

        $this->gateway = Omnipay::create('GlobalAlipay_App');
        $this->gateway->setPartner('123456');
        $this->gateway->setSellerId('foo@example.com');
        $this->gateway->setPrivateKey(__DIR__ . '/Assets/private_key.pem');
        $this->gateway->setNotifyUrl('http://example.com/notify');
    }


    public function testPurchase()
    {
        $order = array(
            'subject'      => 'test', //Your title
            'out_trade_no' => date('YmdHis'), //Should be format 'YmdHis'
            'total_fee'    => '0.01', //Order Title
            'body'         => 'descccccc', //Order Title
        );

        /**
         * @var AppPurchaseResponse $response
         */
        $response = $this->gateway->purchase($order)->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNotEmpty($response->getOrderString());
    }


    public function testCompletePurchase()
    {
        $options = array(
            'request_params' => array(
                'out_trade_no' => '123456',
                'sign'         => '123456',
                'sign_type'    => 'RSA',
            ),
        );

        /**
         * @var CompletePurchaseResponse $response
         */
        $response = $this->gateway->completePurchase($options)->send();
        $this->assertFalse($response->isSuccessful());
    }
}
