<?php

namespace Bolt\Extension\Bolt\AmazonApi\ResponseTransformer;

use ApaiIO\ResponseTransformer\ResponseTransformerInterface;
use Bolt\Extension\Bolt\AmazonApi\ApiResponse;
use Bolt\Extension\Bolt\AmazonApi\ApiResponseElement;
use Bolt\Extension\Bolt\AmazonApi\ApiResponseInterface;

/**
 * XML to ApiResponse ParameterBag response transformer.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class XmlToParameterBagObject implements ResponseTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($response)
    {
        $simpleXML = simplexml_load_string($response);
        $transformedResponse = $this->process($simpleXML, true);

        return $transformedResponse;
    }

    /**
     * @param \SimpleXMLElement $simpleXML
     * @param bool              $isRoot
     *
     * @return ApiResponseInterface
     */
    private function process(\SimpleXMLElement $simpleXML, $isRoot = false)
    {
        $response = $isRoot ? new ApiResponse() : new ApiResponseElement();

        foreach ($simpleXML as $key => $value) {
            $key = strtolower($key);
            if ($value instanceof \SimpleXMLElement && (string) $value === '') {
                $value = $this->process($value);
                $response->set($key, $value);
            } else {
                $response->set($key, (string) $value);
            }
        }

        return $response;
    }
}
