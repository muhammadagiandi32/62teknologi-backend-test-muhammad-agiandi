<?php

use App\Http\Controllers\BusinessesController;
use App\Models\Businesses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/business/get', function () {
    return Businesses::with('coordinates')->with('location')->with('categories')->paginate(15);
});

Route::get('/business/search', [BusinessesController::class, 'show'])->name('cari');
Route::get('/business/{id}/edit', [BusinessesController::class, 'edit'])->name('edit');
Route::put('/business/edit/{id}', [BusinessesController::class, 'update'])->name('update');


Route::post('/business', [BusinessesController::class, 'store'])->name('store');
Route::put('/business', [BusinessesController::class, 'update'])->name('update');
Route::delete('/business', [BusinessesController::class, 'destroy'])->name('delete');

// Route::resource('data', BusinessesController::class);
