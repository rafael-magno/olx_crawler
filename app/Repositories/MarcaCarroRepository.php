<?php

namespace App\Repositories;

use App\Entities\MarcaCarro;
use App\Services\OlxCarsCrawlerService;
use App\Utils\Cache;
use Symfony\Component\DomCrawler\Crawler;

class MarcaCarroRepository
{
    const CACHE_KEY = 'marcasCarro';

    private OlxCarsCrawlerService $olxCarsCrawlerService;

    public function __construct(OlxCarsCrawlerService $olxCarsCrawlerService)
    {
        $this->olxCarsCrawlerService = $olxCarsCrawlerService;
    }

    public function buscarTodas(): array
    {
        $marcasCarro = Cache::get(self::CACHE_KEY);

        if (is_null($marcasCarro)) {
            $oxlCrawler = $this->olxCarsCrawlerService->getByUri();

            $selects = $oxlCrawler->filter('select');
            $selectMarcas = $selects->reduce(fn (Crawler $node) => $this->selectMarcas($node));
            $selectMarcas = $selectMarcas->first();

            $marcasCarro = $selectMarcas->children()->each(fn (Crawler $node) => new MarcaCarro($node->text()));
            array_shift($marcasCarro);

            Cache::put(self::CACHE_KEY, $marcasCarro);
        }

        return $marcasCarro;
    }

    public function existe(string $idMarcaCarro): bool
    {
        $marcasCarro = $this->buscarTodas();

        $marcaCarro = array_filter($marcasCarro, fn ($marcaCarro) => $marcaCarro->id == $idMarcaCarro);

        return !empty($marcaCarro);
    }

    private function selectMarcas(Crawler $node): bool
    {
        $option = $node->filter('option')->first();

        return trim($option->text()) == "Marca";
    }
}
