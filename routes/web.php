<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/csvForm',[\App\Http\Controllers\CsvController::class,'index']);
Route::post('/uploadCsv',[\App\Http\Controllers\CsvController::class,'uploadCsv'])->name('uploadCsv');
