<?php

namespace Bolt\Extension\Bolt\AmazonApi\Item\Component;

class EditorialReview
{
    /** @var string */
    protected $source;
    /** @var string */
    protected $content;
    /** @var bool */
    protected $isLinkSuppressed;

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->source = $data['source'];
        $this->content = $data['content'];
        $this->isLinkSuppressed = $data['islinksuppressed'];
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     *
     * @return EditorialReview
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return EditorialReview
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsLinkSuppressed()
    {
        return $this->isLinkSuppressed;
    }

    /**
     * @param boolean $isLinkSuppressed
     *
     * @return EditorialReview
     */
    public function setIsLinkSuppressed($isLinkSuppressed)
    {
        $this->isLinkSuppressed = $isLinkSuppressed;

        return $this;
    }
}
