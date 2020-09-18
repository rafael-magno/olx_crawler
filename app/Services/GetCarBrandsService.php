<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;

class GetCarBrandsService
{
    private $oxlCrawler;

    public function __construct(OlxCarsCrawlerService $olxCarsCrawlerService)
    {
        $this->oxlCrawler = $olxCarsCrawlerService->getByUri();
    }

    public function handle(): array
    {
        $selects = $this->oxlCrawler->filter('select');
        $selectBrands = $selects->reduce(fn (Crawler $node) => $this->isBrandSelect($node));
        $selectBrands = $selectBrands->first();

        $brands = $selectBrands->children()->each(fn (Crawler $node) => $node->text());
        array_shift($brands);

        return $brands;
    }

    private function isBrandSelect(Crawler $node): bool
    {
        $option = $node->filter('option')->first();

        return trim($option->text()) == "Marca";
    }
}
