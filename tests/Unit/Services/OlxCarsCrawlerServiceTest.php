<?php

namespace Tests\Unit\Services;

use App\Services\OlxCarsCrawlerService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

/**
 * @covers \App\Services\OlxCarsCrawlerService
 *
 * @internal
 */
class OlxCarsCrawlerServiceTest extends TestCase
{
    public function testMontagemCrawler()
    {
        $mockResponseBody = '
            <html>
                <head>
                    <title>Título Teste</title>
                </head>
                <body>Body Teste</body>
            </html>
        ';

        $olxCarsCrawlerService = self::getInstace([
            new Response(200, [], $mockResponseBody)
        ]);

        $crawler = $olxCarsCrawlerService->getByUri();

        $childrenHtml = $crawler->children();
        $childrenHead = $childrenHtml->first()->children();
        $nodeTitle = $childrenHead->first();
        $nodeBody = $childrenHtml->last();

        $this->assertEquals('html', $crawler->nodeName());
        $this->assertEquals(
            ['head', 'body'],
            $childrenHtml->each(fn (Crawler $node) => $node->nodeName())
        );
        $this->assertEquals(
            ['title'],
            $childrenHead->each(fn (Crawler $node) => $node->nodeName())
        );
        $this->assertEquals(0, $nodeTitle->children()->count());
        $this->assertEquals('Título Teste', $nodeTitle->text());
        $this->assertEquals(0, $nodeBody->children()->count());
        $this->assertEquals('Body Teste', $nodeBody->text());
    }

    public static function getInstace(array $responseMocks): OlxCarsCrawlerService
    {
        $clientHttp = new Client([
            'handler' => MockHandler::createWithMiddleware($responseMocks)
        ]);

        return new OlxCarsCrawlerService($clientHttp, new Crawler());
    }
}
