<?php

namespace App\Entities;

use Illuminate\Support\Str;
use JsonSerializable;

class CarBrand implements JsonSerializable
{
    private string $id;
    private string $name;

    public function __construct(string $name)
    {
        $this->id = Str::slug($name);
        $this->name = $name;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
