<?php

namespace App\Repositories;

use App\Entities\Anuncio;
use App\Entities\Carro;
use App\Services\OlxCarsCrawlerService;
use App\Utils\Cache;
use Symfony\Component\DomCrawler\Crawler;

class AnuncioRepository
{
    const CACHE_KEY = 'anuncios|';

    private OlxCarsCrawlerService $olxCarsCrawlerService;

    public function __construct(OlxCarsCrawlerService $olxCarsCrawlerService)
    {
        $this->olxCarsCrawlerService = $olxCarsCrawlerService;
    }

    public function buscarPorMarcaModelo(string $idMarcaCarro, string $idModeloCarro, int $pagina): array
    {
        $cacheKey = self::CACHE_KEY . $idMarcaCarro . '|' . $idModeloCarro . '|' . $pagina;
        $anuncios = Cache::get($cacheKey);

        if (is_null($anuncios)) {
            $oxlCrawler = $this->olxCarsCrawlerService->getByUri(
                $idMarcaCarro . '/' . $idModeloCarro . '?o=' . $pagina
            );

            $linkAnuncios = $oxlCrawler->filter('ul#ad-list a[data-lurker_list_id]');
            $anuncios = $linkAnuncios->each(function (Crawler $node) use ($idMarcaCarro, $idModeloCarro) {
                return $this->montaAnuncio($node, $idMarcaCarro, $idModeloCarro);
            });

            Cache::put($cacheKey, $anuncios);
        }

        return $anuncios;
    }

    private function montaAnuncio(Crawler $node, string $idMarcaCarro, string $idModeloCarro)
    {
        $preco = $node->filter('span:contains("R$")');
        $preco = $preco->count()
            ? (int) str_replace(['R$ ', '.', ','], ['', '', '.'], $preco->first()->text())
            : null;

        $data = $node->filter('span:contains(":")')
            ->reduce(fn (Crawler $node) => (bool) preg_match('/[0-9]:[0-5]/', $node->text()))
            ->parents()
            ->first()
            ->children()
            ->each(fn (Crawler $node) => $node->text());

        return new Anuncio(
            $this->montaCarro($node, $idMarcaCarro, $idModeloCarro),
            $node->attr('href'),
            $node->attr('title'),
            $node->filter('img')->first()->attr('src'),
            $preco,
            implode(' ', $data),
            $node->filter('span:contains(" - ")')->first()->attr('title')
        );
    }

    private function montaCarro(Crawler $node, string $idMarcaCarro, string $idModeloCarro)
    {
        $detalhes = $node->filter('span:contains(" | ")');
        $detalhes = $detalhes->count() ? $detalhes->first()->attr('title') : '';
        $detalhes = explode(' | ', $detalhes);

        foreach ($detalhes as $detalhe) {
            if (strpos($detalhe, ' km') !== FALSE) {
                $quilometragem = str_replace(['.', ' km'], '', $detalhe);
            } else if (strpos($detalhe, 'Câmbio: ') !== FALSE) {
                $cambio = str_replace('Câmbio: ', '', $detalhe);
            } else {
                $combustivel = $detalhe;
            }
        }

        return new Carro(
            $idMarcaCarro,
            $idModeloCarro,
            $quilometragem ?? null,
            $cambio ?? null,
            $combustivel ?? null,
        );
    }
}
