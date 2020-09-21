<?php

namespace Tests\Unit\Http\Actions;

use App\Exceptions\HttpUnprocessableEntityException;
use App\Http\Actions\BuscaModelosCarroAction;
use App\Repositories\MarcaCarroRepository;
use App\Repositories\ModeloCarroRepository;
use App\Utils\Cache;
use Tests\TestCase;
use Tests\Unit\Repositories\MarcaCarroRepositoryTest;
use Tests\Unit\Repositories\ModeloCarroRepositoryTest;

/**
 * @covers \App\Http\Actions\BuscaModelosCarroAction
 *
 * @internal
 */
class BuscaModelosCarroActionTest extends TestCase
{
    private static $mockMarca = 'fiat';
    private static $mockModelos = ['147', 'uno'];

    protected function setUp(): void
    {
        parent::setUp();

        Cache::put(
            MarcaCarroRepository::CACHE_KEY,
            MarcaCarroRepositoryTest::getRetornoEsperado([self::$mockMarca])
        );

        Cache::put(
            ModeloCarroRepository::CACHE_KEY . self::$mockMarca,
            ModeloCarroRepositoryTest::getRetornoEsperado(self::$mockMarca, self::$mockModelos)
        );
    }

    public function testSucessoAction()
    {
        $esperado = ModeloCarroRepositoryTest::getRetornoEsperado(self::$mockMarca, self::$mockModelos);

        $buscaModelosCarroAction = app(BuscaModelosCarroAction::class);

        $modelosCarro = $buscaModelosCarroAction(
            self::$mockMarca,
            app(ModeloCarroRepository::class),
            app(MarcaCarroRepository::class),
        );

        $this->assertEquals($esperado, $modelosCarro);
    }

    public function testMarcaInexistenteAction()
    {
        $buscaModelosCarroAction = app(BuscaModelosCarroAction::class);
        $mensagemErro = null;

        try {
            $buscaModelosCarroAction(
                'vw',
                app(ModeloCarroRepository::class),
                app(MarcaCarroRepository::class),
            );
        } catch (HttpUnprocessableEntityException $e) {
            $jsonContent = $e->getResponse()->getContent();
            $content = json_decode($jsonContent);
            $mensagemErro = $content->erro;
        }

        $this->assertEquals(
            BuscaModelosCarroAction::MARCA_CARRO_NAO_ENCONTRADA,
            $mensagemErro
        );
    }
}
