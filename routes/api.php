<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/v1/login', [ApiController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/v1/invoice', [ApiController::class, 'InvoiceList']);
});
