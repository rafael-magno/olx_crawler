<?php

namespace Tests\Unit\Http\Actions;

use App\Http\Actions\BuscaMarcasCarroAction;
use App\Repositories\MarcaCarroRepository;
use App\Utils\Cache;
use Tests\TestCase;
use Tests\Unit\Repositories\MarcaCarroRepositoryTest;

/**
 * @covers \App\Http\Actions\BuscaMarcasCarroAction
 *
 * @internal
 */
class BuscaMarcasCarroActionTest extends TestCase
{
    public function testSucessoAction()
    {
        $esperado = MarcaCarroRepositoryTest::getRetornoEsperado(['fiat', 'citroen']);

        Cache::put(MarcaCarroRepository::CACHE_KEY, $esperado);

        $buscaMarcasCarroAction = app(BuscaMarcasCarroAction::class);

        $marcaCarroRepository = app(MarcaCarroRepository::class);

        $this->assertEquals($esperado, $buscaMarcasCarroAction($marcaCarroRepository));
    }
}
