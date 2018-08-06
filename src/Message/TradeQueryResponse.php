<?php

namespace Omnipay\GlobalAlipay\Message;

use Omnipay\Common\Message\AbstractResponse;

class TradeQueryResponse extends AbstractResponse
{
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
}
