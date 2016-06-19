<?php

namespace Bolt\Extension\Bolt\AmazonApi\Item\Component;

/**
 * Image component.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class Image
{
    /** @var string */
    protected $url;
    /** @var int */
    protected $height;
    /** @var int */
    protected $width;

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->url = $data['url'];
        $this->height = $data['height'];
        $this->width = $data['width'];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
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
     * @return Image
     */
    public function setHeight($height)
    {
        $this->height = $height;

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
     * @return Image
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }
}
