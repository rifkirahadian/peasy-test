<?php

use App\Http\Controllers\DailyRecordController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [UserController::class, 'index']);
Route::get('user/datatable', [UserController::class, 'datatable']);
Route::delete('user/{id}', [UserController::class, 'destroy']);
Route::get('daily-record/datatable', [DailyRecordController::class, 'datatable']);
