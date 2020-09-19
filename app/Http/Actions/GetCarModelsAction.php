<?php

namespace App\Http\Actions;

use App\Exceptions\HttpUnprocessableEntityException;
use App\Repositories\CarBrandRepository;
use App\Repositories\CarModelRepository;
use Illuminate\Routing\Controller;

class GetCarModelsAction extends Controller
{
    public function __invoke(
        string $carBrand,
        CarModelRepository $carModelRepository,
        CarBrandRepository $carBrandRepository
    ): array
    {
        if (!$carBrandRepository->exists($carBrand)) {
            throw new HttpUnprocessableEntityException('Car brand not found.');
        }

        return $carModelRepository->findByBrand($carBrand);
    }
}
