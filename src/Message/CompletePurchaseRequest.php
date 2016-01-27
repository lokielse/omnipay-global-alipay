<?php

namespace Omnipay\GlobalAlipay\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\GlobalAlipay\Helper;

class CompletePurchaseRequest extends AbstractRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $data = array (
            'request_params' => $this->getRequestParams()
        );

        return $data;
    }


    public function setRequestParams($value)
    {
        $this->setParameter('request_params', $value);
    }


    public function getRequestParams()
    {
        return $this->getParameter('request_params');
    }


    public function getKey()
    {
        return $this->getParameter('key');
    }


    public function setKey($value)
    {
        return $this->setParameter('key', $value);
    }


    public function getPartner()
    {
        return $this->getParameter('partner');
    }


    public function setPartner($value)
    {
        return $this->setParameter('partner', $value);
    }


    public function getEnvironment()
    {
        return $this->getParameter('environment');
    }


    public function setEnvironment($value)
    {
        return $this->setParameter('environment', $value);
    }


    protected function getRequestParam($key)
    {
        $params = $this->getRequestParams();

        return isset($params[$key]) ? $params[$key] : null;
    }


    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $data = $this->getRequestParams();

        $sign = Helper::sign($data, $this->getRequestParam('sign_type'), $this->getKey());

        $responseData = array ();

        if (isset($data['sign']) && $data['sign'] && $sign === $data['sign']) {
            $responseData['sign_match'] = true;
        } else {
            $responseData['sign_match'] = false;
        }

        if ($responseData['sign_match'] && isset($data['trade_status']) && $data['trade_status'] == 'TRADE_FINISHED') {
            $responseData['paid'] = true;
        } else {
            $responseData['paid'] = false;
        }

        return $this->response = new CompletePurchaseResponse($this, $responseData);
    }
}
