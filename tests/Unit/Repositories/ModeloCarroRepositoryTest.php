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
        $esperado = self::getRetornoEsperado($mockMarca, ['147', 'uno']);
        $mockResponseBody = "
            <html>
                <body>
                    <select>
                        <option>Modelo</option>
        ";

        foreach ($esperado as $modelo) {
            $mockResponseBody .= "<option>{$modelo->nome}</option>";
        }

        $mockResponseBody .= "
                    </select>
                </body>
            </html>
        ";

        $modeloCarroRepository = self::getInstace([
            new Response(200, [], $mockResponseBody)
        ]);

        $modelosCarro = $modeloCarroRepository->buscarPorMarca($mockMarca);

        $this->assertEquals($esperado, $modelosCarro);
        $this->assertEquals(
            Cache::get(ModeloCarroRepository::CACHE_KEY . $mockMarca),
            $modelosCarro
        );
    }

    public function testMetodoExisteVerdadeiro()
    {
        $mockMarca = 'fiat';
        $modelosCarro = self::getRetornoEsperado($mockMarca, ['147', 'uno']);

        $modeloCarroRepository = app(ModeloCarroRepository::class);

        Cache::put(ModeloCarroRepository::CACHE_KEY . $mockMarca, $modelosCarro);

        foreach ($modelosCarro as $modeloCarro) {
            $this->assertEquals(
                true,
                $modeloCarroRepository->existe($mockMarca, $modeloCarro->id)
            );
        }
    }

    public function testMetodoExisteFalso()
    {
        $mockMarca = 'fiat';
        $modelosCarro = self::getRetornoEsperado($mockMarca, ['147', 'uno']);

        $modeloCarroRepository = app(ModeloCarroRepository::class);

        Cache::put(ModeloCarroRepository::CACHE_KEY . $mockMarca, $modelosCarro);

        $this->assertEquals(
            false,
            $modeloCarroRepository->existe($mockMarca, 'vw')
        );
    }

    public static function getRetornoEsperado(string $marca, array $modelos)
    {
        return array_map(
            fn ($modelo) => new ModeloCarro($marca, $modelo),
            $modelos
        );
    }

    public static function getInstace(array $responseMocks)
    {
        $olxCarsCrawler = OlxCarsCrawlerServiceTest::getInstace($responseMocks);

        return new ModeloCarroRepository($olxCarsCrawler);
    }
}
