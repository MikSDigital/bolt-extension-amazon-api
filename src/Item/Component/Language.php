<?php

namespace Bolt\Extension\Bolt\AmazonApi\Item\Component;

class Language
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $type;

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->type = $data['type'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Language
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Language
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
