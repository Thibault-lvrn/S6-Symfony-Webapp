<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GotenbergClient
{
    private $client;
    private $gotenbergUrl;
    private $test;

    public function __construct(HttpClientInterface $client, ParameterBagInterface $parameterBagInterface, string $gotenbergUrl)
    {
        $this->client = $client;
        $this->gotenbergUrl = $gotenbergUrl;
        $this->test = $parameterBagInterface->get('gotenberg_url');
    }

    public function convertUrlToPdf(string $url): string
    {
        $response = $this->client->request('POST', $this->gotenbergUrl, [
            'headers' => [
                'Content-Type' => 'multipart/form-data',
            ],
            'body' => [
                'url' => $url,
            ],
        ]);

        return $response->getContent();
    }
}
