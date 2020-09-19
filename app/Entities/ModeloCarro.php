<?php

namespace App\Entities;

use App\Utils\MagicGet;
use Illuminate\Support\Str;
use JsonSerializable;

class ModeloCarro implements JsonSerializable
{
    use MagicGet;

    private string $marca;
    private string $id;
    private string $nome;

    public function __construct(string $marca, string $nome)
    {
        $this->marca = $marca;
        $this->id = Str::slug($nome);
        $this->nome = $nome;
    }

    public function jsonSerialize()
    {
        return [
            'marca' => $this->marca,
            'id' => $this->id,
            'nome' => $this->nome,
        ];
    }
}
