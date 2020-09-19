<?php

namespace App\Repositories;

use App\Entities\CarBrand;
use App\Services\OlxCarsCrawlerService;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\DomCrawler\Crawler;

class CarBrandRepository
{
    const CACHE_KEY = 'carBrands';

    private OlxCarsCrawlerService $olxCarsCrawlerService;

    public function __construct(OlxCarsCrawlerService $olxCarsCrawlerService)
    {
        $this->olxCarsCrawlerService = $olxCarsCrawlerService;
    }

    public function findAll(): array
    {
        $brands = Cache::get(self::CACHE_KEY);

        if (is_null($brands)) {
            $oxlCrawler = $this->olxCarsCrawlerService->getByUri();

            $selects = $oxlCrawler->filter('select');
            $selectBrands = $selects->reduce(fn (Crawler $node) => $this->isBrandSelect($node));
            $selectBrands = $selectBrands->first();

            $brands = $selectBrands->children()->each(fn (Crawler $node) => new CarBrand($node->text()));
            array_shift($brands);

            Cache::put(self::CACHE_KEY, $brands, config('cache_time'));
        }

        return $brands;
    }

    private function isBrandSelect(Crawler $node): bool
    {
        $option = $node->filter('option')->first();

        return trim($option->text()) == "Marca";
    }
}
