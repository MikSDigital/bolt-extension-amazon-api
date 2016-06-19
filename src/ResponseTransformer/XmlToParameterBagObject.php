<?php

namespace Bolt\Extension\Bolt\AmazonApi\ResponseTransformer;

use ApaiIO\ResponseTransformer\ResponseTransformerInterface;
use Bolt\Extension\Bolt\AmazonApi\Response\AbstractApiResponse;
use Bolt\Extension\Bolt\AmazonApi\Response\ApiResponse;
use Bolt\Extension\Bolt\AmazonApi\Response\ApiResponseElement;

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
     * @return AbstractApiResponse
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
