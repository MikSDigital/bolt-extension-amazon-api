<?php

namespace Bolt\Extension\Bolt\AmazonApi;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * API response elements.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class ApiResponseElement extends ParameterBag implements ApiResponseInterface
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
