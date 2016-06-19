<?php

namespace Bolt\Extension\Bolt\AmazonApi\Item\Component;

class Price
{
    /** @var float */
    protected $amount;
    /** @var string */
    protected $currencyCode;
    /** @var string */
    protected $formattedPrice;

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->amount = $data['amount'];
        $this->currencyCode = $data['currencycode'];
        $this->formattedPrice = $data['formattedprice'];
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return Price
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     *
     * @return Price
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormattedPrice()
    {
        return $this->formattedPrice;
    }

    /**
     * @param string $formattedPrice
     *
     * @return Price
     */
    public function setFormattedPrice($formattedPrice)
    {
        $this->formattedPrice = $formattedPrice;

        return $this;
    }
}
