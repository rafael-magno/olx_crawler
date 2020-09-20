<?php

namespace App\Entities;

use App\Utils\MagicGet;
use Illuminate\Support\Str;
use JsonSerializable;

/**
 * @property Carro $carro
 * @property string $link
 * @property string $titulo
 * @property string $imagem
 * @property float $preco
 * @property string $data
 * @property string $cidade
 */
class Anuncio implements JsonSerializable
{
    use MagicGet;

    private Carro $carro;
    private string $link;
    private string $titulo;
    private string $imagem;
    private ?float $preco;
    private string $data;
    private string $cidade;

    public function __construct(
        Carro $carro,
        string $link,
        string $titulo,
        string $imagem,
        ?float $preco,
        string $data,
        string $cidade
    ) {
        $this->carro = $carro;
        $this->link = $link;
        $this->titulo = $titulo;
        $this->imagem = $imagem;
        $this->preco = $preco;
        $this->data = $data;
        $this->cidade = $cidade;
    }

    public function jsonSerialize()
    {
        return [
            'carro' => $this->carro,
            'link' => $this->link,
            'titulo' => $this->titulo,
            'imagem' => $this->imagem,
            'preco' => $this->preco,
            'data' => $this->data,
            'cidade' => $this->cidade,
        ];
    }
}
