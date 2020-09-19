<?php

namespace App\Repositories;

use App\Entities\ModeloCarro;
use App\Services\OlxCarsCrawlerService;
use App\Utils\Cache;
use Symfony\Component\DomCrawler\Crawler;

class ModeloCarroRepository
{
    const CACHE_KEY = 'modelosCarro|';

    private OlxCarsCrawlerService $olxCarsCrawlerService;

    public function __construct(OlxCarsCrawlerService $olxCarsCrawlerService)
    {
        $this->olxCarsCrawlerService = $olxCarsCrawlerService;
    }

    public function buscarPorMarca(string $idMarcaCarro): array
    {
        $cacheKey = self::CACHE_KEY . $idMarcaCarro;
        $modelosCarro = Cache::get($cacheKey);

        if (is_null($modelosCarro)) {
            $oxlCrawler = $this->olxCarsCrawlerService->getByUri($idMarcaCarro);

            $selects = $oxlCrawler->filter('select');
            $selectModelos = $selects->reduce(fn (Crawler $node) => $this->selectModelo($node));
            $selectModelos = $selectModelos->first();

            $modelosCarro = $selectModelos->children()->each(fn (Crawler $node) => new ModeloCarro($idMarcaCarro, $node->text()));
            array_shift($modelosCarro);

            Cache::put($cacheKey, $modelosCarro);
        }

        return $modelosCarro;
    }

    private function selectModelo(Crawler $node): bool
    {
        $option = $node->filter('option')->first();

        return trim($option->text()) == "Modelo";
    }
}
