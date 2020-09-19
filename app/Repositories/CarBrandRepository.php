<?php

namespace App\Repositories;

use App\Entities\CarBrand;
use App\Services\OlxCarsCrawlerService;
use App\Utils\Cache;
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
        $carBrands = Cache::get(self::CACHE_KEY);

        if (is_null($carBrands)) {
            $oxlCrawler = $this->olxCarsCrawlerService->getByUri();

            $selects = $oxlCrawler->filter('select');
            $selectBrands = $selects->reduce(fn (Crawler $node) => $this->isBrandSelect($node));
            $selectBrands = $selectBrands->first();

            $carBrands = $selectBrands->children()->each(fn (Crawler $node) => new CarBrand($node->text()));
            array_shift($carBrands);

            Cache::put(self::CACHE_KEY, $carBrands);
        }

        return $carBrands;
    }

    public function exists(string $carBrandId): bool
    {
        $carBrands = $this->findAll();

        $carBrand = array_filter($carBrands, fn ($carBrand) => $carBrand->id == $carBrandId);

        return !empty($carBrand);
    }

    private function isBrandSelect(Crawler $node): bool
    {
        $option = $node->filter('option')->first();

        return trim($option->text()) == "Marca";
    }
}
