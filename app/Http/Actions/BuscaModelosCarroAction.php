<?php

namespace App\Http\Actions;

use App\Exceptions\HttpUnprocessableEntityException;
use App\Repositories\MarcaCarroRepository;
use App\Repositories\ModeloCarroRepository;
use Illuminate\Routing\Controller;

class BuscaModelosCarroAction extends Controller
{
    public function __invoke(
        string $idMarcaCarro,
        ModeloCarroRepository $modeloCarroRepository,
        MarcaCarroRepository $marcaCarroRepository
    ): array
    {
        if (!$marcaCarroRepository->existe($idMarcaCarro)) {
            throw new HttpUnprocessableEntityException('Car brand not found.');
        }

        return $modeloCarroRepository->buscarPorMarca($idMarcaCarro);
    }
}
