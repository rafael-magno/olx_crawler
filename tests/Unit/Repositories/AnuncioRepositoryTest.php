<?php

namespace Tests\Unit\Repositories;

use App\Entities\Anuncio;
use App\Entities\Carro;
use App\Repositories\AnuncioRepository;
use App\Utils\Cache;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use Tests\Unit\Services\OlxCarsCrawlerServiceTest;

/**
 * @covers \App\Repositories\AnuncioRepository
 *
 * @internal
 */
class AnuncioRepositoryTest extends TestCase
{
    public function testBuscarPorMarcaModelo()
    {
        $mockMarca = 'fiat';
        $mockModelo = 'uno';
        $esperado = self::getRetornoEsperado($mockMarca, $mockModelo);
        $mockResponseBody = self::getMockResponseBody($esperado);

        $anuncioRepository = self::getInstace([
            new Response(200, [], $mockResponseBody)
        ]);

        $anuncios = $anuncioRepository->buscarPorMarcaModelo($mockMarca, $mockModelo, 1);

        $this->assertEquals($esperado, $anuncios);
        $this->assertEquals(
            Cache::get(AnuncioRepository::CACHE_KEY . $mockMarca . '|' . $mockModelo . '|1'),
            $anuncios
        );
    }

    public static function getRetornoEsperado(string $mockMarca, string $mockModelo): array
    {
        return [
            new Anuncio(
                new Carro(
                    $mockMarca,
                    $mockModelo,
                    25000,
                    'Manual',
                    'Flex'
                ),
                'http://teste.com/0',
                'Titulo uno 0',
                'http://teste.com/0.png',
                15000,
                'Ontem 20:15',
                'Belo Horizonte - MG'
            ),
            new Anuncio(
                new Carro(
                    $mockMarca,
                    $mockModelo,
                    50000,
                    'Automático',
                    'Gasolina'
                ),
                'http://teste.com/1',
                'Titulo uno 1',
                'http://teste.com/1.png',
                25000,
                'Ontem 19:15',
                'Betim - MG'
            ),
        ];
    }

    public static function getMockResponseBody(array $anuncios): string
    {
        $mockResponseBody = "
            <html>
                <body>
                    <ul id='ad-list'>
        ";

        foreach ($anuncios as $anuncio) {
            $quilometragem = number_format($anuncio->carro->quilometragem, 0, ',', '.');
            $detalhes = "$quilometragem km | Câmbio: {$anuncio->carro->cambio} | {$anuncio->carro->combustivel}";
            $preco = number_format($anuncio->preco, 0, ',', '.');
            list($dia, $hora) = explode(' ', $anuncio->data);

            $mockResponseBody .= "
                <li>
                    <a data-lurker_list_id='1' href='{$anuncio->link}' title='{$anuncio->titulo}'>
                        <img src='{$anuncio->imagem}' />
                        <span title='$detalhes'>$detalhes</span>
                        <span>R$ $preco</span>
                        <div>
                            <span>$dia</span>
                            <span>$hora</span>
                        </div>
                        <span title='{$anuncio->cidade}'>{$anuncio->cidade}</span>
                    </a>
                </li>
            ";
        }

        $mockResponseBody .= "
                    </ul>
                </body>
            </html>
        ";

        return $mockResponseBody;
    }
    public static function getInstace(array $responseMocks)
    {
        $olxCarsCrawler = OlxCarsCrawlerServiceTest::getInstace($responseMocks);

        return new AnuncioRepository($olxCarsCrawler);
    }
}
