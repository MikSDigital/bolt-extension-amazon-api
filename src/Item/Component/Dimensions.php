<?php

namespace Bolt\Extension\Bolt\AmazonApi\Item\Component;

class Dimensions
{
    /** @var int */
    protected $height;
    /** @var int */
    protected $length;
    /** @var int */
    protected $weight;
    /** @var int */
    protected $width;

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->height = $data['height'];
        $this->length = $data['length'];
        $this->weight = $data['weight'];
        $this->width = $data['width'];
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     *
     * @return Dimensions
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int $length
     *
     * @return Dimensions
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     *
     * @return Dimensions
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return Dimensions
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }
}
