<?php
namespace Omnipay\GlobalAlipay\Message;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
class TradeQueryRequest extends AbstractRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     * @return mixed
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'out_trade_no'
        );
        $data = array(
            'service'        => 'single_trade_query',
            'partner'        => $this->getPartner(),
            '_input_charset' => $this->getInputCharset() ?: 'utf-8',//<>
            'sign_type'      => 'RSA',
            'out_trade_no'   => $this->getOutTradeNo(),
        );
        return $data;
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
        /**
         * is paid?
         */
        if (isset($data['trade_status']) && $data['trade_status'] == 'TRADE_FINISHED') {
            $paid = true;
        } else {
            $paid = false;
        }
        $responseData = array(
            'paid' => $paid
        );
        return $this->response = new TradeQueryResponse($this, $responseData);
    }
    public function getPrivateKey()
    {
        return $this->getParameter('private_key');
    }
    public function setPrivateKey($value)
    {
        return $this->setParameter('private_key', $value);
    }
    public function getPartner()
    {
        return $this->getParameter('partner');
    }
    public function setPartner($value)
    {
        return $this->setParameter('partner', $value);
    }
    public function getPaymentType()
    {
        return $this->getParameter('payment_type');
    }
    public function setPaymentType($value)
    {
        return $this->setParameter('payment_type', $value);
    }
    public function getInputCharset()
    {
        return $this->getParameter('input_charset');
    }
    public function setInputCharset($value)
    {
        return $this->setParameter('input_charset', $value);
    }
    public function getAppId()
    {
        return $this->getParameter('app_id');
    }
    public function setAppId($value)
    {
        return $this->setParameter('app_id', $value);
    }
    public function getAppEnv()
    {
        return $this->getParameter('app_env');
    }
    public function setAppEnv($value)
    {
        return $this->setParameter('app_env', $value);
    }
    public function getExternToken()
    {
        return $this->getParameter('extern_token');
    }
    public function setExternToken($value)
    {
        return $this->setParameter('extern_token', $value);
    }
    public function getOutTradeNo()
    {
        return $this->getParameter('out_trade_no');
    }
    public function setOutTradeNo($value)
    {
        return $this->setParameter('out_trade_no', $value);
    }
    public function getForexBiz()
    {
        return $this->getParameter('forex_biz');
    }
    public function setForexBiz($value)
    {
        return $this->setParameter('forex_biz', $value);
    }
    public function setRequestParams($value)
    {
        $this->setParameter('request_params', $value);
    }
    public function getRequestParams()
    {
        return $this->getParameter('request_params');
    }
}
