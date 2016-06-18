<?php

namespace Bolt\Extension\Bolt\AmazonApi;

use Symfony\Component\HttpFoundation\ParameterBag;

class Config extends ParameterBag
{
    /**
     * @return array
     */
    public function getResponseGroup()
    {
        return $this->get('responsegroup');
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->get('country');
    }

    /**
     * @return string
     */
    public function getAccessKey()
    {
        return $this->get('accesskey');
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->get('secretkey');
    }

    /**
     * @return string
     */
    public function getAssociateTag()
    {
        return $this->get('associatetag');
    }
}
