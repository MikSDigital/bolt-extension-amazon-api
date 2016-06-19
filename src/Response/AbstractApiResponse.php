<?php

namespace Bolt\Extension\Bolt\AmazonApi\Response;

use Symfony\Component\HttpFoundation\ParameterBag;

abstract class AbstractApiResponse extends ParameterBag
{
    /**
     * {@inheritdoc}
     */
    public function all()
    {
        $all = parent::all();
        foreach ($all as $key => $value) {
            if ($value instanceof ParameterBag) {
                $all[$key] = $value->all();
            }
        }

        return $all;
    }
}
