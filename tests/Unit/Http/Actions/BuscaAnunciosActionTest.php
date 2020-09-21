<?php

namespace Tests\Unit\Http\Actions;

use App\Exceptions\HttpUnprocessableEntityException;
use App\Http\Actions\BuscaAnunciosAction;
use App\Repositories\AnuncioRepository;
use App\Repositories\MarcaCarroRepository;
use App\Repositories\ModeloCarroRepository;
use App\Utils\Cache;
use Tests\TestCase;
use Tests\Unit\Repositories\AnuncioRepositoryTest;
use Tests\Unit\Repositories\MarcaCarroRepositoryTest;
use Tests\Unit\Repositories\ModeloCarroRepositoryTest;

/**
 * @covers \App\Http\Actions\BuscaAnunciosAction
 *
 * @internal
 */
class BuscaAnunciosActionTest extends TestCase
{
    private static $mockMarca = 'fiat';
    private static $mockModelo = 'uno';
    private static $mockPagina = 1;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::put(
            MarcaCarroRepository::CACHE_KEY,
            MarcaCarroRepositoryTest::getRetornoEsperado([self::$mockMarca])
        );

        Cache::put(
            ModeloCarroRepository::CACHE_KEY . self::$mockMarca,
            ModeloCarroRepositoryTest::getRetornoEsperado(self::$mockMarca, [self::$mockModelo])
        );

        Cache::put(
            AnuncioRepository::CACHE_KEY . self::$mockMarca . '|' . self::$mockModelo . '|' . self::$mockPagina,
            AnuncioRepositoryTest::getRetornoEsperado(self::$mockMarca, self::$mockModelo)
        );
    }

    public function testSucessoAction()
    {
        $esperado = AnuncioRepositoryTest::getRetornoEsperado(self::$mockMarca, self::$mockModelo);

        $buscaAnunciosAction = app(BuscaAnunciosAction::class);

        $anuncios = $buscaAnunciosAction(
            self::$mockMarca,
            self::$mockModelo,
            self::$mockPagina,
            app(MarcaCarroRepository::class),
            app(ModeloCarroRepository::class),
            app(AnuncioRepository::class),
        );

        $this->assertEquals($esperado, $anuncios);
    }

    /**
     * @dataProvider dadosTesteErros
     */
    public function testErrosAction(string $marca, string $modelo, string $erroEsperado)
    {
        $buscaAnunciosAction = app(BuscaAnunciosAction::class);
        $mensagemErro = null;

        try {
            $buscaAnunciosAction(
                $marca,
                $modelo,
                self::$mockPagina,
                app(MarcaCarroRepository::class),
                app(ModeloCarroRepository::class),
                app(AnuncioRepository::class),
            );
        } catch (HttpUnprocessableEntityException $e) {
            $jsonContent = $e->getResponse()->getContent();
            $content = json_decode($jsonContent);
            $mensagemErro = $content->erro;
        }

        $this->assertEquals($erroEsperado, $mensagemErro);
    }

    public function dadosTesteErros()
    {
        return [
            ['vw', 'vw', BuscaAnunciosAction::MARCA_CARRO_NAO_ENCONTRADA],
            [self::$mockMarca, 'vw', BuscaAnunciosAction::MODELO_CARRO_NAO_ENCONTRADO],
        ];
    }
}
