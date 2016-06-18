<?php

namespace Bolt\Extension\Bolt\AmazonApi;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * API responses.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class ApiResponse extends AbstractApiResponse
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
}
