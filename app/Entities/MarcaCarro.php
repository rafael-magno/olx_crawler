<?php

namespace App\Entities;

use App\Utils\MagicGet;
use Illuminate\Support\Str;
use JsonSerializable;

/**
 * @property string $id
 * @property string $nome
 */
class MarcaCarro implements JsonSerializable
{
    use MagicGet;

    private string $id;
    private string $nome;

    public function __construct(string $nome)
    {
        $this->id = Str::slug($nome);
        $this->nome = $nome;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
        ];
    }
}
