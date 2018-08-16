<?php

namespace Omnipay\GlobalAlipay;

use Omnipay\Common\AbstractGateway;

class WebGateway extends AbstractGateway
{

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'Global Alipay web gateway';
    }


    public function setPrivateKey($value)
    {
        return $this->setParameter('private_key', $value);
    }


    public function getPrivateKey()
    {
        return $this->getParameter('private_key');
    }

    /**
     * @return mixed
     */
    public function getAlipayPublicKey()
    {
        return $this->getParameter('alipay_public_key');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setAlipayPublicKey($value)
    {
        return $this->setParameter('alipay_public_key', $value);
    }


    public function getEnvironment()
    {
        return $this->getParameter('environment');
    }


    public function setEnvironment($value)
    {
        return $this->setParameter('environment', $value);
    }


    public function setKey($value)
    {
        return $this->setParameter('key', $value);
    }


    public function getKey()
    {
        return $this->getParameter('key');
    }


    public function getPartner()
    {
        return $this->getParameter('partner');
    }


    public function setPartner($value)
    {
        return $this->setParameter('partner', $value);
    }


    public function getNotifyUrl()
    {
        return $this->getParameter('notify_url');
    }


    public function setNotifyUrl($value)
    {
        return $this->setParameter('notify_url', $value);
    }


    public function getReturnUrl()
    {
        return $this->getParameter('return_url');
    }


    public function setReturnUrl($value)
    {
        return $this->setParameter('return_url', $value);
    }


    public function getSignType()
    {
        return $this->getParameter('sign_type');
    }


    public function setSignType($value)
    {
        return $this->setParameter('sign_type', $value);
    }


    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\GlobalAlipay\Message\WebPurchaseRequest', $parameters);
    }


    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\GlobalAlipay\Message\CompletePurchaseRequest', $parameters);
    }

    public function query(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\GlobalAlipay\Message\TradeQueryRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\GlobalAlipay\Message\TradeRefundRequest', $parameters);
    }

    public function getCaCertPath()
    {
        return $this->getParameter('ca_cert_path');
    }


    public function setCaCertPath($value)
    {
        return $this->setParameter('ca_cert_path', $value);
    }
}
