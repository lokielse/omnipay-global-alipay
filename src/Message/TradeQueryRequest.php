<?php

namespace Omnipay\GlobalAlipay\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\GlobalAlipay\Common\Signer;

class TradeQueryRequest extends AbstractRequest
{
    protected $endpoint = 'https://mapi.alipay.com/gateway.do';

    protected $endpointSandbox = 'https://openapi.alipaydev.com/gateway.do';

    protected $service = 'single_trade_query';

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     * @return mixed
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'partner'
        );

        $data = [
            'out_trade_no' => $this->getOutTradeNo(),
            'service' => $this->service,
            '_input_charset' => $this->getInputCharset() ?: 'utf-8',
            'sign_type' => 'RSA',
            'partner' => $this->getPartner(),
        ];

        ksort($data);

        $data['sign'] = $this->sign($data, 'RSA');

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
        $method = $this->getRequestMethod();
        $url = $this->getEndpoint();
        $body = http_build_query($data);
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $response = $this->httpClient->request($method, $url, $headers, $body);

        $payload = $this->decode($response->getBody());

        /**
         * is paid?
         */
        if (isset($payload['is_success']) && $payload['is_success'] == 'T' && array_get($payload, 'response.trade.trade_status') == 'TRADE_FINISHED') {
            $paid = true;
        } else {
            $paid = false;
        }

        $payload['paid'] = $paid;

        return $this->response = new TradeQueryResponse($this, $payload);
    }

    /**
     * @return string
     */
    protected function getRequestMethod()
    {
        return 'POST';
    }

    /**
     * @param $data
     *
     * @return string
     */
    protected function getRequestUrl($data)
    {
        $queryParams = $data;

        unset($queryParams['biz_content']);
        ksort($queryParams);

        $url = sprintf('%s?%s', $this->getEndpoint(), http_build_query($queryParams));

        return $url;
    }


    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        if ($this->getEnvironment() == 'sandbox') {
            return $this->endpointSandbox;
        } else {
            return $this->endpoint;
        }
    }

    public function getEnvironment()
    {
        return $this->getParameter('environment');
    }


    public function setEnvironment($value)
    {
        return $this->setParameter('environment', $value);
    }

    public function getPrivateKey()
    {
        return $this->getParameter('private_key');
    }

    public function setPrivateKey($value)
    {
        return $this->setParameter('private_key', $value);
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

    public function validateParam()
    {
        foreach (func_get_args() as $key) {
            $value = $this->getRequestParam($key);
            if (empty($value)) {
                throw new InvalidRequestException("The $key of request_params is required");
            }
        }
    }

    protected function getRequestParam($key)
    {
        $params = $this->getRequestParams();

        return isset($params[$key]) ? $params[$key] : null;
    }

    public function getTransport()
    {
        return $this->getParameter('transport');
    }


    public function setTransport($value)
    {
        return $this->setParameter('transport', $value);
    }

    protected function sign($params, $signType)
    {
        $signer = new Signer($params);

        $signType = strtoupper($signType);

        if ($signType == 'RSA') {
            $sign = $signer->signWithRSA($this->getPrivateKey());
        } elseif ($signType == 'MD5') {
            $sign = $signer->signWithMD5($this->getkey());
        } else {
            throw new InvalidRequestException('The signType is invalid');
        }

        return $sign;
    }

    protected function decode($data)
    {
        $postObj = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        return json_decode(json_encode($postObj), true);
    }
}
