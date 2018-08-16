<?php

namespace Omnipay\GlobalAlipay\Message;

use Omnipay\Common\Message\AbstractResponse;

class TradeQueryResponse extends AbstractResponse
{
    protected $key = 'response.trade';

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->isPaid();
    }

    public function isPaid()
    {
        $data = $this->getData();
        return $data['paid'];
    }

    public function getAlipayResponse($key = null)
    {
        if ($key) {
            return array_get($this->data, "$this->key.{$key}");
        } else {
            return array_get($this->data, $this->key);
        }
    }
}
