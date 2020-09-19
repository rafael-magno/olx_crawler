<?php

namespace App\Entities;

use App\Utils\MagicGet;
use Illuminate\Support\Str;
use JsonSerializable;

class CarModel implements JsonSerializable
{
    use MagicGet;

    private string $brand;
    private string $id;
    private string $name;

    public function __construct(string $brand, string $name)
    {
        $this->brand = $brand;
        $this->id = Str::slug($name);
        $this->name = $name;
    }

    public function jsonSerialize()
    {
        return [
            'brand' => $this->brand,
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
