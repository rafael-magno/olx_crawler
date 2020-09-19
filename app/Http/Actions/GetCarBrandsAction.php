<?php

namespace App\Http\Actions;

use App\Repositories\CarBrandRepository;
use Illuminate\Routing\Controller;

class GetCarBrandsAction extends Controller
{
    public function __invoke(CarBrandRepository $repository): array
    {
        return $repository->findAll();
    }
}
