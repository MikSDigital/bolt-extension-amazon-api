<?php

namespace Bolt\Extension\Bolt\AmazonApi;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * API responses
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class ApiResponse extends ParameterBag implements ApiResponseInterface
{
    /**
     * Check if the Amazon response was an error response.
     *
     * @return bool
     */
    public function isError()
    {
        return $this->get('items')->get('request')->has('errors');
    }

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
