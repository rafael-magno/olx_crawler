<?php

namespace App\Http\Actions;

use App\Repositories\MarcaCarroRepository;
use Illuminate\Routing\Controller;

class BuscaMarcasCarroAction extends Controller
{
    public function __invoke(MarcaCarroRepository $marcaCarroRepository): array
    {
        return $marcaCarroRepository->buscarTodas();
    }
}
