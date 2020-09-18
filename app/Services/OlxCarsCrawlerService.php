<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class OlxCarsCrawlerService
{
    const BASE_URI = 'https://www.olx.com.br/autos-e-pecas/carros-vans-e-utilitarios/';

    private Client $httpClient;
    private Crawler $crawler;

    public function __construct(Client $httpClient, Crawler $crawler)
    {
        $this->httpClient = $httpClient;
        $this->crawler = $crawler;
    }

    public function getByUri(string $uri = ""): Crawler
    {
        $response = $this->httpClient->get(self::BASE_URI . $uri);
        $responseBody = (string) $response->getBody();

        $this->crawler->add($responseBody);

        return $this->crawler;
    }
}
