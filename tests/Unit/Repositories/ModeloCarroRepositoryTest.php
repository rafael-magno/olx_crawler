<?php

namespace Tests\Unit\Repositories;

use App\Entities\ModeloCarro;
use App\Repositories\ModeloCarroRepository;
use App\Utils\Cache;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use Tests\Unit\Services\OlxCarsCrawlerServiceTest;

/**
 * @covers \App\Repositories\ModeloCarroRepository
 *
 * @internal
 */
class ModeloCarroRepositoryTest extends TestCase
{
    public function testBuscarPorMarca()
    {
        $mockMarca = 'fiat';
        $mockModelos = ['147', 'Uno'];
        $mockResponseBody = "
            <html>
                <body>
                    <select>
                        <option>Modelo</option>
                        <option>{$mockModelos[0]}</option>
                        <option>{$mockModelos[1]}</option>
                    </select>
                </body>
            </html>
        ";

        $modeloCarroRepository = self::getInstace([
            new Response(200, [], $mockResponseBody)
        ]);

        $modelosCarro = $modeloCarroRepository->buscarPorMarca($mockMarca);

        $esperado = array_map(
            fn ($modelo) => new ModeloCarro($mockMarca, $modelo),
            $mockModelos
        );

        $this->assertEquals($esperado, $modelosCarro);
        $this->assertEquals(
            Cache::get(ModeloCarroRepository::CACHE_KEY . $mockMarca),
            $modelosCarro
        );

        return [
            $mockMarca,
            $modelosCarro
        ];
    }

    /**
     * @depends testBuscarPorMarca
     */
    public function testMetodoExisteVerdadeiro(array $dadosTeste)
    {
        list($mockMarca, $modelosCarro) = $dadosTeste;

        $modeloCarroRepository = app(ModeloCarroRepository::class);

        Cache::put(ModeloCarroRepository::CACHE_KEY . $mockMarca, $modelosCarro);

        foreach ($modelosCarro as $modeloCarro) {
            $this->assertEquals(
                true,
                $modeloCarroRepository->existe($mockMarca, $modeloCarro->id)
            );
        }
    }

    /**
     * @depends testBuscarPorMarca
     */
    public function testMetodoExisteFalso(array $dadosTeste)
    {
        list($mockMarca, $modelosCarro) = $dadosTeste;

        $modeloCarroRepository = app(ModeloCarroRepository::class);

        Cache::put(ModeloCarroRepository::CACHE_KEY . $mockMarca, $modelosCarro);

        $this->assertEquals(
            false,
            $modeloCarroRepository->existe($mockMarca, 'vw')
        );
    }

    public static function getInstace(array $responseMocks)
    {
        $olxCarsCrawler = OlxCarsCrawlerServiceTest::getInstace($responseMocks);

        return new ModeloCarroRepository($olxCarsCrawler);
    }
}
