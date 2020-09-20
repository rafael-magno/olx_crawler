<?php

namespace Tests\Unit\Entities;

use App\Entities\Carro;
use Tests\TestCase;

/**
 * @covers \App\Entities\Carro
 *
 * @internal
 */
class CarroTest extends TestCase
{
    public function testMontagemEntidade()
    {
        $esperado = [
            'marca' => 'fiat',
            'modelo' => 'uno',
            'quilometragem' => 10000,
            'cambio' => 'Manual',
            'combustivel' => 'Flex',
        ];

        $marcaCarro = new Carro(
            $esperado['marca'],
            $esperado['modelo'],
            $esperado['quilometragem'],
            $esperado['cambio'],
            $esperado['combustivel']
        );

        $this->assertEquals($esperado['marca'], $marcaCarro->marca);
        $this->assertEquals($esperado['modelo'], $marcaCarro->modelo);
        $this->assertEquals($esperado['quilometragem'], $marcaCarro->quilometragem);
        $this->assertEquals($esperado['cambio'], $marcaCarro->cambio);
        $this->assertEquals($esperado['combustivel'], $marcaCarro->combustivel);
        $this->assertEquals(
            json_encode($esperado),
            json_encode($marcaCarro)
        );
    }
}
