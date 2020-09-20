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

        $carro = new Carro(
            $esperado['marca'],
            $esperado['modelo'],
            $esperado['quilometragem'],
            $esperado['cambio'],
            $esperado['combustivel']
        );

        $this->assertEquals($esperado['marca'], $carro->marca);
        $this->assertEquals($esperado['modelo'], $carro->modelo);
        $this->assertEquals($esperado['quilometragem'], $carro->quilometragem);
        $this->assertEquals($esperado['cambio'], $carro->cambio);
        $this->assertEquals($esperado['combustivel'], $carro->combustivel);
        $this->assertEquals(
            json_encode($esperado),
            json_encode($carro)
        );
    }
}
