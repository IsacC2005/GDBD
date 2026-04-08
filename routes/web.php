<?php

use App\Http\Controllers\FacturaPdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/facturas/{factura}/pdf', FacturaPdfController::class)
        ->name('facturas.pdf.download');
});
