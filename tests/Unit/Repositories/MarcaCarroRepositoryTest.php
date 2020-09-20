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
        $mockMarcas = ['Fiat', 'Citroen'];
        $mockResponseBody = "
            <html>
                <body>
                    <select>
                        <option>Marca</option>
                        <option>{$mockMarcas[0]}</option>
                        <option>{$mockMarcas[1]}</option>
                    </select>
                </body>
            </html>
        ";

        $marcaCarroRepository = self::getInstace([
            new Response(200, [], $mockResponseBody)
        ]);

        $marcasCarro = $marcaCarroRepository->buscarTodas();

        $esperado = array_map(fn ($marca) => new MarcaCarro($marca), $mockMarcas);

        $this->assertEquals($esperado, $marcasCarro);
        $this->assertEquals(
            Cache::get(MarcaCarroRepository::CACHE_KEY),
            $marcasCarro
        );

        return $marcasCarro;
    }

    /**
     * @depends testBuscarTodas
     */
    public function testMetodoExisteVerdadeiro(array $marcasCarro)
    {
        $marcaCarroRepository = app(MarcaCarroRepository::class);

        Cache::put(MarcaCarroRepository::CACHE_KEY, $marcasCarro);

        foreach ($marcasCarro as $marcaCarro) {
            $this->assertEquals(
                true,
                $marcaCarroRepository->existe($marcaCarro->id)
            );
        }
    }

    /**
     * @depends testBuscarTodas
     */
    public function testMetodoExisteFalso(array $marcasCarro)
    {
        $marcaCarroRepository = app(MarcaCarroRepository::class);

        Cache::put(MarcaCarroRepository::CACHE_KEY, $marcasCarro);

        $this->assertEquals(
            false,
            $marcaCarroRepository->existe('vw')
        );
    }

    public static function getInstace(array $responseMocks)
    {
        $olxCarsCrawler = OlxCarsCrawlerServiceTest::getInstace($responseMocks);

        return new MarcaCarroRepository($olxCarsCrawler);
    }
}
