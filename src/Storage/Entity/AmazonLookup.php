<?php

namespace Bolt\Extension\Bolt\AmazonApi\Storage\Entity;

use Bolt\Storage\Entity\Entity;

/**
 * Amazon lookup entity.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class AmazonLookup extends Entity
{
    /** @var string */
    protected $asin;
    /** @var array */
    protected $item;
    /** @var \DateTime */
    protected $cached;

    /**
     * @return string
     */
    public function getAsin()
    {
        return $this->asin;
    }

    /**
     * @param string $asin
     */
    public function setAsin($asin)
    {
        $this->asin = $asin;
    }

    /**
     * @return array
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param array $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }

    /**
     * @return \DateTime
     */
    public function getCached()
    {
        return $this->cached;
    }

    /**
     * @param \DateTime $cached
     */
    public function setCached($cached)
    {
        $this->cached = $cached;
    }
}
