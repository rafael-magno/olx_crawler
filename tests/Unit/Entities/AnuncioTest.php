<?php

namespace Tests\Unit\Entities;

use App\Entities\Anuncio;
use App\Entities\Carro;
use Tests\TestCase;

/**
 * @covers \App\Entities\Carro
 *
 * @internal
 */
class AnuncioTest extends TestCase
{
    public function testMontagemEntidade()
    {
        $esperado = [
            'carro' => [
                'marca' => 'fiat',
                'modelo' => 'uno',
                'quilometragem' => 10000,
                'cambio' => 'Manual',
                'combustivel' => 'Flex',
            ],
            'link' => 'http://teste.com',
            'titulo' => 'Teste título',
            'imagem' => 'http://teste.com/imagem.jpg',
            'preco' => 15000,
            'data' => 'Ontem 20:05',
            'cidade' => 'Belo Horizonte - MG',
        ];

        $anuncio = new Anuncio(
            new Carro(
                $esperado['carro']['marca'],
                $esperado['carro']['modelo'],
                $esperado['carro']['quilometragem'],
                $esperado['carro']['cambio'],
                $esperado['carro']['combustivel']
            ),
            $esperado['link'],
            $esperado['titulo'],
            $esperado['imagem'],
            $esperado['preco'],
            $esperado['data'],
            $esperado['cidade']
        );

        $this->assertEquals($esperado['carro']['marca'], $anuncio->carro->marca);
        $this->assertEquals($esperado['carro']['modelo'], $anuncio->carro->modelo);
        $this->assertEquals($esperado['carro']['quilometragem'], $anuncio->carro->quilometragem);
        $this->assertEquals($esperado['carro']['cambio'], $anuncio->carro->cambio);
        $this->assertEquals($esperado['carro']['combustivel'], $anuncio->carro->combustivel);
        $this->assertEquals($esperado['link'], $anuncio->link);
        $this->assertEquals($esperado['titulo'], $anuncio->titulo);
        $this->assertEquals($esperado['imagem'], $anuncio->imagem);
        $this->assertEquals($esperado['preco'], $anuncio->preco);
        $this->assertEquals($esperado['data'], $anuncio->data);
        $this->assertEquals($esperado['cidade'], $anuncio->cidade);
        $this->assertEquals(
            json_encode($esperado),
            json_encode($anuncio)
        );
    }
}
