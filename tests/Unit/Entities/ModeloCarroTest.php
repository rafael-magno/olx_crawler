<?php

namespace Tests\Unit\Entities;

use App\Entities\ModeloCarro;
use Tests\TestCase;

/**
 * @covers \App\Entities\ModeloCarro
 *
 * @internal
 */
class ModeloCarroTest extends TestCase
{
    public function testMontagemEntidade()
    {
        $esperado = [
            'marca' => 'fiat',
            'id' => 'uno',
            'nome' => 'Uno',
        ];

        $modeloCarro = new ModeloCarro($esperado['marca'], $esperado['nome']);

        $this->assertEquals($esperado['marca'], $modeloCarro->marca);
        $this->assertEquals($esperado['id'], $modeloCarro->id);
        $this->assertEquals($esperado['nome'], $modeloCarro->nome);
        $this->assertEquals(
            json_encode($esperado),
            json_encode($modeloCarro)
        );
    }
}
