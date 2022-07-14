<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::resource('forms', FormController::class);

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/', [FormController::class, 'index']
)->middleware(['auth'])->name('site.forms');

Route::get('/modify', [FormController::class, 'edit']
)->middleware(['auth'])->name('site.modify');

Route::get('/statics', [FormController::class, 'show']
)->middleware(['auth'])->name('site.statics');

Route::get('/create', function(){
    return view('site.new-form');
})->middleware(['auth'])->name('site.new-form');

require __DIR__.'/auth.php';
