<?php

namespace App\Http\Actions;

use App\Exceptions\HttpUnprocessableEntityException;
use App\Repositories\AnuncioRepository;
use App\Repositories\MarcaCarroRepository;
use App\Repositories\ModeloCarroRepository;
use Illuminate\Routing\Controller;

class BuscaAnunciosAction extends Controller
{
    const MARCA_CARRO_NAO_ENCONTRADA = 'Marca de carro não encontrada.';
    const MODELO_CARRO_NAO_ENCONTRADO = 'Modelo de carro não encontrado.';

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
            throw new HttpUnprocessableEntityException(self::MARCA_CARRO_NAO_ENCONTRADA);
        }

        if (!$modeloCarroRepository->existe($idMarcaCarro, $idModeloCarro)) {
            throw new HttpUnprocessableEntityException(self::MODELO_CARRO_NAO_ENCONTRADO);
        }

        return $anuncioRepository->buscarPorMarcaModelo($idMarcaCarro, $idModeloCarro, $pagina);
    }
}
