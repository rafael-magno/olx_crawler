<?php

use App\Http\Actions\GetCarBrandsAction;
use App\Http\Actions\GetCarModelsAction;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/car-brand', GetCarBrandsAction::class);
Route::get('/car-model/{brand}', GetCarModelsAction::class);
