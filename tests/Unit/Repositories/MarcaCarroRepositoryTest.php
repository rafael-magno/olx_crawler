<?php

namespace Tests\Unit\Repositories;

use App\Entities\MarcaCarro;
use App\Repositories\MarcaCarroRepository;
use App\Utils\Cache;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use Tests\Unit\Services\OlxCarsCrawlerServiceTest;

/**
 * @covers \App\Repositories\MarcaCarroRepository
 *
 * @internal
 */
class MarcaCarroRepositoryTest extends TestCase
{
    public function testBuscarTodas()
    {
        $esperado = self::getRetornoEsperado(['Fiat', 'Citroen']);
        $mockResponseBody = "
            <html>
                <body>
                    <select>
                        <option>Marca</option>
        ";

        foreach ($esperado as $marca) {
            $mockResponseBody .= "<option>{$marca->nome}</option>";
        }

        $mockResponseBody .= "
                    </select>
                </body>
            </html>
        ";

        $marcaCarroRepository = self::getInstace([
            new Response(200, [], $mockResponseBody)
        ]);

        $marcasCarro = $marcaCarroRepository->buscarTodas();

        $this->assertEquals($esperado, $marcasCarro);
        $this->assertEquals(
            Cache::get(MarcaCarroRepository::CACHE_KEY),
            $marcasCarro
        );

        return $marcasCarro;
    }

    public function testMetodoExisteVerdadeiro()
    {
        $marcasCarro = self::getRetornoEsperado(['Fiat', 'Citroen']);

        $marcaCarroRepository = app(MarcaCarroRepository::class);

        Cache::put(MarcaCarroRepository::CACHE_KEY, $marcasCarro);

        foreach ($marcasCarro as $marcaCarro) {
            $this->assertEquals(
                true,
                $marcaCarroRepository->existe($marcaCarro->id)
            );
        }
    }

    public function testMetodoExisteFalso()
    {
        $marcasCarro = self::getRetornoEsperado(['Fiat', 'Citroen']);

        $marcaCarroRepository = app(MarcaCarroRepository::class);

        Cache::put(MarcaCarroRepository::CACHE_KEY, $marcasCarro);

        $this->assertEquals(
            false,
            $marcaCarroRepository->existe('vw')
        );
    }

    public static function getRetornoEsperado(array $marcas): array
    {
        return array_map(fn ($marca) => new MarcaCarro($marca), $marcas);
    }

    public static function getInstace(array $responseMocks)
    {
        $olxCarsCrawler = OlxCarsCrawlerServiceTest::getInstace($responseMocks);

        return new MarcaCarroRepository($olxCarsCrawler);
    }
}
