<?php

use App\Http\Actions\BuscaAnunciosAction;
use App\Http\Actions\BuscaMarcasCarroAction;
use App\Http\Actions\BuscaModelosCarroAction;
use Illuminate\Support\Facades\Route;

Route::get('/marca-carro', BuscaMarcasCarroAction::class);
Route::get('/modelo-carro/{idMarcaCarro}', BuscaModelosCarroAction::class);
Route::get('/anuncio/{idMarcaCarro}/{idModeloCarro}/{pagina?}', BuscaAnunciosAction::class);
