<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/rodar-migrations-secreta', function () {
    // Roda o comando forçando (pois é produção)
    Artisan::call('migrate:fresh --seed --force');
    
    return "Banco resetado e populado com sucesso! <br>" . nl2br(Artisan::output());
});