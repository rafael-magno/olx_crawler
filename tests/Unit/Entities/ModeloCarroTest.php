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

        $marcaCarro = new ModeloCarro($esperado['marca'], $esperado['nome']);

        $this->assertEquals($esperado['marca'], $marcaCarro->marca);
        $this->assertEquals($esperado['id'], $marcaCarro->id);
        $this->assertEquals($esperado['nome'], $marcaCarro->nome);
        $this->assertEquals(
            json_encode($esperado),
            json_encode($marcaCarro)
        );
    }
}
