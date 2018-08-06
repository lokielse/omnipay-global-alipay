<?php

namespace Omnipay\GlobalAlipay\Message;

use Omnipay\Common\Message\AbstractResponse;

class TradeRefundResponse extends AbstractResponse
{
    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful ()
    {
        return $this->isRefunded();
    }

    public function isRefunded ()
    {
        $data = $this->getData();
        return $data['refunded'];
    }
}
