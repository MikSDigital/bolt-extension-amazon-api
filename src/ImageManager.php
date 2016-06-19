<?php

namespace Bolt\Extension\Bolt\AmazonApi;

use Bolt\Filesystem\Manager;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Image download manager.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class ImageManager
{
    /** @var Config */
    private $config;
    /** @var Client */
    private $client;
    /** @var Manager */
    private $filesystemManager;

    /**
     * Constructor.
     *
     * @param Config  $config
     * @param Client  $client
     * @param Manager $filesystemManager
     */
    public function __construct(Config $config, Client $client, Manager $filesystemManager)
    {
        $this->config = $config;
        $this->client = $client;
        $this->filesystemManager = $filesystemManager;
    }

    /**
     * Check that our remote exists
     *
     * @param string $url
     *
     * @return boolean
     */
    public function remoteExists($url)
    {
        // Throttle the request, if required
        Utils::requestThrottle();

        $headers = ['User-Agent' => $this->config->get('user_agent')];

        $request = new Psr7Request('HEAD', $url, $headers);
        /** @var ResponseInterface $response */
        $response = $this->client->send($request);

        return $response->getStatusCode() === 200;
    }

    /**
     * Download a file from a given URL to a given destination file/location
     *
     * @param string $url
     * @param string $targetFile
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function downloadFile($url, $targetFile)
    {
        // Throttle the request, if required
        Utils::requestThrottle();

        $headers = ['User-Agent' => $this->config->get('user_agent')];

        $request = new Psr7Request('GET', $url, $headers);
        $response = $this->client->send($request);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new \RuntimeException(sprintf('Unable to download file from %s', $url));
        }

        $file = $this->filesystemManager->getFile('files://' . $targetFile);
        $file->putStream($response->getBody());
    }
}
