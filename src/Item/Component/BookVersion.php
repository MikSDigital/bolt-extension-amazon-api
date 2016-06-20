<?php

namespace Bolt\Extension\Bolt\AmazonApi\Item\Component;

class BookVersion
{
    /** @var string */
    protected $asin;
    /** @var string */
    protected $title;
    /** @var string */
    protected $binding;

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->asin = $data['asin'];
        $this->title = $data['title'];
        $this->binding = $data['binding'];
    }

    /**
     * @return string
     */
    public function getAsin()
    {
        return $this->asin;
    }

    /**
     * @param string $asin
     *
     * @return BookVersion
     */
    public function setAsin($asin)
    {
        $this->asin = $asin;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return BookVersion
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getBinding()
    {
        return $this->binding;
    }

    /**
     * @param string $binding
     *
     * @return BookVersion
     */
    public function setBinding($binding)
    {
        $this->binding = $binding;

        return $this;
    }
}
