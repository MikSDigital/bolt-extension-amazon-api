<?php

namespace Bolt\Extension\Bolt\AmazonApi\Item\Component;

class OfferSummary
{
    /** @var Price */
    protected $lowestNewPrice;
    /** @var Price */
    protected $lowestUsedPrice;
    /** @var Price */
    protected $lowestCollectiblePrice;
    /** @var int */
    protected $totalNew;
    /** @var int */
    protected $totalUsed;
    /** @var int */
    protected $totalCollectible;
    /** @var int */
    protected $totalRefurbished;

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->lowestNewPrice = isset($data['lowestnewprice']) ? new Price($data['lowestnewprice']) : null;
        $this->lowestUsedPrice = isset($data['lowestusedprice']) ? new Price($data['lowestusedprice']) : null;
        $this->lowestCollectiblePrice = isset($data['lowestcollectibleprice']) ? new Price($data['lowestcollectibleprice']) : null;
        $this->totalNew = $data['totalnew'];
        $this->totalUsed = $data['totalused'];
        $this->totalCollectible = $data['totalcollectible'];
        $this->totalRefurbished = $data['totalrefurbished'];
    }

    /**
     * @return Price
     */
    public function getLowestNewPrice()
    {
        return $this->lowestNewPrice;
    }

    /**
     * @param Price $lowestNewPrice
     *
     * @return OfferSummary
     */
    public function setLowestNewPrice($lowestNewPrice)
    {
        $this->lowestNewPrice = $lowestNewPrice;

        return $this;
    }

    /**
     * @return Price
     */
    public function getLowestUsedPrice()
    {
        return $this->lowestUsedPrice;
    }

    /**
     * @param Price $lowestUsedPrice
     *
     * @return OfferSummary
     */
    public function setLowestUsedPrice($lowestUsedPrice)
    {
        $this->lowestUsedPrice = $lowestUsedPrice;

        return $this;
    }

    /**
     * @return Price
     */
    public function getLowestCollectiblePrice()
    {
        return $this->lowestCollectiblePrice;
    }

    /**
     * @param Price $lowestCollectiblePrice
     *
     * @return OfferSummary
     */
    public function setLowestCollectiblePrice($lowestCollectiblePrice)
    {
        $this->lowestCollectiblePrice = $lowestCollectiblePrice;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalNew()
    {
        return $this->totalNew;
    }

    /**
     * @param int $totalNew
     *
     * @return OfferSummary
     */
    public function setTotalNew($totalNew)
    {
        $this->totalNew = $totalNew;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalUsed()
    {
        return $this->totalUsed;
    }

    /**
     * @param int $totalUsed
     *
     * @return OfferSummary
     */
    public function setTotalUsed($totalUsed)
    {
        $this->totalUsed = $totalUsed;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalCollectible()
    {
        return $this->totalCollectible;
    }

    /**
     * @param int $totalCollectible
     *
     * @return OfferSummary
     */
    public function setTotalCollectible($totalCollectible)
    {
        $this->totalCollectible = $totalCollectible;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalRefurbished()
    {
        return $this->totalRefurbished;
    }

    /**
     * @param int $totalRefurbished
     *
     * @return OfferSummary
     */
    public function setTotalRefurbished($totalRefurbished)
    {
        $this->totalRefurbished = $totalRefurbished;

        return $this;
    }
}
