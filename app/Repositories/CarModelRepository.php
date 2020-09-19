<?php

namespace App\Repositories;

use App\Entities\CarModel;
use App\Services\OlxCarsCrawlerService;
use App\Utils\Cache;
use Symfony\Component\DomCrawler\Crawler;

class CarModelRepository
{
    const CACHE_KEY = 'carModels|';

    private OlxCarsCrawlerService $olxCarsCrawlerService;

    public function __construct(OlxCarsCrawlerService $olxCarsCrawlerService)
    {
        $this->olxCarsCrawlerService = $olxCarsCrawlerService;
    }

    public function findByBrand(string $carBrand): array
    {
        $cacheKey = self::CACHE_KEY . $carBrand;
        $carModels = Cache::get($cacheKey);

        if (is_null($carModels)) {
            $oxlCrawler = $this->olxCarsCrawlerService->getByUri($carBrand);

            $selects = $oxlCrawler->filter('select');
            $selectModels = $selects->reduce(fn (Crawler $node) => $this->isModelSelect($node));
            $selectModels = $selectModels->first();

            $carModels = $selectModels->children()->each(fn (Crawler $node) => new CarModel($carBrand, $node->text()));
            array_shift($carModels);

            Cache::put($cacheKey, $carModels, config('cache.time'));
        }

        return $carModels;
    }

    private function isModelSelect(Crawler $node): bool
    {
        $option = $node->filter('option')->first();

        return trim($option->text()) == "Modelo";
    }
}
