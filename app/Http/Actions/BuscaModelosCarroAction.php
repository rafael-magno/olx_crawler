<?php

namespace App\Http\Actions;

use App\Exceptions\HttpUnprocessableEntityException;
use App\Repositories\MarcaCarroRepository;
use App\Repositories\ModeloCarroRepository;
use Illuminate\Routing\Controller;

class BuscaModelosCarroAction extends Controller
{
    const MARCA_CARRO_NAO_ENCONTRADA = 'Marca de carro não encontrada.';

    public function __invoke(
        string $idMarcaCarro,
        ModeloCarroRepository $modeloCarroRepository,
        MarcaCarroRepository $marcaCarroRepository
    ): array
    {
        if (!$marcaCarroRepository->existe($idMarcaCarro)) {
            throw new HttpUnprocessableEntityException(self::MARCA_CARRO_NAO_ENCONTRADA);
        }

        return $modeloCarroRepository->buscarPorMarca($idMarcaCarro);
    }
}
