<?php

namespace Tests\Unit\Entities;

use App\Entities\MarcaCarro;
use Tests\TestCase;

/**
 * @covers \App\Entities\MarcaCarro
 *
 * @internal
 */
class MarcaCarroTest extends TestCase
{
    public function testMontagemEntidade()
    {
        $esperado = [
            'id' => 'fiat',
            'nome' => 'Fiat',
        ];

        $marcaCarro = new MarcaCarro($esperado['nome']);

        $this->assertEquals($esperado['id'], $marcaCarro->id);
        $this->assertEquals($esperado['nome'], $marcaCarro->nome);
        $this->assertEquals(
            json_encode($esperado),
            json_encode($marcaCarro)
        );
    }
}
