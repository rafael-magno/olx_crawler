<?php

namespace App\Http\Actions;

use App\Services\GetCarBrandsService;
use Illuminate\Routing\Controller;

class GetCarBrandsAction extends Controller
{
    private GetCarBrandsService $getCarBrandsService;

    public function __construct(GetCarBrandsService $getCarBrandsService)
    {
        $this->getCarBrandsService = $getCarBrandsService;
    }

    public function __invoke(): array
    {
        return $this->getCarBrandsService->handle();
    }
}
