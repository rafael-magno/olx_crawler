<?php

namespace App\Entities;

use App\Utils\MagicGet;
use JsonSerializable;

/**
 * @property string $marca
 * @property string $modelo
 * @property int $quilometragem
 * @property string $cambio
 * @property string $combustivel
 */
class Carro implements JsonSerializable
{
    use MagicGet;

    private string $marca;
    private string $modelo;
    private ?int $quilometragem;
    private ?string $cambio;
    private ?string $combustivel;

    public function __construct(
        string $marca,
        string $modelo,
        ?int $quilometragem = null,
        ?string $cambio = null,
        ?string $combustivel = null
    )
    {
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->quilometragem = $quilometragem;
        $this->cambio = $cambio;
        $this->combustivel = $combustivel;
    }

    public function jsonSerialize()
    {
        return [
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'quilometragem' => $this->quilometragem,
            'cambio' => $this->cambio,
            'combustivel' => $this->combustivel,
        ];
    }
}
