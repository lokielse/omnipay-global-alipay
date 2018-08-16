<?php

namespace Omnipay\GlobalAlipay\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\GlobalAlipay\Common\Signer;

class TradeRefundRequest extends AbstractRequest
{
    protected $endpoint = 'https://mapi.alipay.com/gateway.do';

    protected $endpointSandbox = 'https://openapi.alipaydev.com/gateway.do';

    protected $service = 'forex_refund';

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     * @return mixed
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'partner',
            'out_return_no',
            'out_trade_no',
            'currency',
            'product_code'
        );

        if ($this->getReturnAmount() && $this->getReturnRmbAmount()) {
            throw new InvalidRequestException("The 'return_amount' and 'return_rmb_amount' parameter can not be provide together");
        }

        if (!$this->getReturnAmount() && !$this->getReturnRmbAmount()) {
            throw new InvalidRequestException("The 'return_amount' and 'return_rmb_amount' must be provide one of them");
        }

        $data = [
            'service' => $this->service,
            'partner' => $this->getPartner(),
            'out_trade_no' => $this->getOutTradeNo(),
            '_input_charset' => $this->getInputCharset() ?: 'utf-8',
            'sign_type' => 'RSA',
            'is_sync' => $this->getIsSync() ?: 'Y', //default sync
            'out_return_no' => $this->getOutReturnNo(),
            'return_amount' => $this->getReturnAmount(),
            'return_rmb_amount' => $this->getReturnRmbAmount(),
            'currency' => $this->getCurrency() ?: 'USD', //default USD
            'reason' => $this->getReason(),
            'product_code' => $this->getProductCode()
        ];

        $data = array_filter($data);

        ksort($data);

        $data['sign'] = $this->sign($data, $data['sign_type']);

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
         * is refund?
         */
        if (isset($payload['is_success']) && $payload['is_success'] == 'T') {
            $refunded = true;
        } else {
            $refunded = false;
        }

        $payload['refunded'] = $refunded;

        return $this->response = new TradeRefundResponse($this, $payload);
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

    public function getPartner()
    {
        return $this->getParameter('partner');
    }

    public function setPartner($value)
    {
        return $this->setParameter('partner', $value);
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

    public function getOutTradeNo()
    {
        return $this->getParameter('out_trade_no');
    }

    public function setOutTradeNo($value)
    {
        return $this->setParameter('out_trade_no', $value);
    }

    public function setRequestParams($value)
    {
        $this->setParameter('request_params', $value);
    }

    public function getRequestParams()
    {
        return $this->getParameter('request_params');
    }

    public function getReturnAmount()
    {
        return $this->getParameter('return_amount');
    }


    public function setReturnAmount($value)
    {
        return $this->setParameter('return_amount', $value);
    }


    public function getReturnRmbAmount()
    {
        return $this->getParameter('return_rmb_amount');
    }


    public function setReturnRmbAmount($value)
    {
        return $this->setParameter('return_rmb_amount', $value);
    }

    public function getIsSync()
    {
        return $this->getParameter('is_sync');
    }

    public function setIsSync($value)
    {
        return $this->setParameter('is_sync', $value);
    }

    public function getOutReturnNo()
    {
        return $this->getParameter('out_return_no');
    }


    public function setOutReturnNo($value)
    {
        return $this->setParameter('out_return_no', $value);
    }

    public function getReason()
    {
        return $this->getParameter('reason');
    }

    public function setReason($value)
    {
        return $this->setParameter('reason', $value);
    }

    public function getProductCode()
    {
        return $this->getParameter('product_code');
    }

    public function setProductCode($value)
    {
        return $this->setParameter('product_code', $value);
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
        } elseif ($signType == 'RSA2') {
            $sign = $signer->signWithRSA($this->getPrivateKey(), OPENSSL_ALGO_SHA256);
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
