<?php

use App\Http\Controllers\BusinessesController;
use App\Models\Businesses;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // return Businesses::with('coordinates')->with('location')->with('categories')->get();
    $aws =   Storage::disk('s3')->get('images/1702892029-1e8cc36e-bc19-4d43-90df-6c2b741cc62534C9D421-999A-44F5-B599-67485797EFB0.JPG');
});
