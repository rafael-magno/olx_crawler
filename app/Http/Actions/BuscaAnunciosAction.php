<?php

namespace App\Http\Actions;

use App\Exceptions\HttpUnprocessableEntityException;
use App\Repositories\AnuncioRepository;
use App\Repositories\MarcaCarroRepository;
use App\Repositories\ModeloCarroRepository;
use Illuminate\Routing\Controller;

class BuscaAnunciosAction extends Controller
{
    public function __invoke(
        string $idMarcaCarro,
        string $idModeloCarro,
        int $pagina = 1,
        MarcaCarroRepository $marcaCarroRepository,
        ModeloCarroRepository $modeloCarroRepository,
        AnuncioRepository $anuncioRepository
    ): array
    {
        if (!$marcaCarroRepository->existe($idMarcaCarro)) {
            throw new HttpUnprocessableEntityException('Marca de carro não encontrada.');
        }

        if (!$modeloCarroRepository->existe($idMarcaCarro, $idModeloCarro)) {
            throw new HttpUnprocessableEntityException('Modelo de carro não encontrado.');
        }

        return $anuncioRepository->buscarPorMarcaModelo($idMarcaCarro, $idModeloCarro, $pagina);
    }
}
