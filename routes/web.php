<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BarangController;

Route::resource('barang', BarangController::class);

Route::get('/', function () {
    return view('welcome');
});
