<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/


//routes to manage the espresso machine and get espresso'' s
Route::prefix('espresso-machine')->name('espresso-machine.')->group(function(){
    Route::get('/','App\Http\Controllers\EspressoMachineController@index')->name('index');
    Route::post('config','App\Http\Controllers\EspressoMachineController@config')->name('config');
    Route::post('{modelEspressoMachine}/config','App\Http\Controllers\EspressoMachineController@reconfig')->name('reconfig');
    Route::delete('{modelEspressoMachine}','App\Http\Controllers\EspressoMachineController@delete')->name('delete');
    Route::get('{modelEspressoMachine}/one','App\Http\Controllers\EspressoController@one')->name('make-one');
    Route::get('{modelEspressoMachine}/double','App\Http\Controllers\EspressoController@double')->name('make-double');
    Route::get('{modelEspressoMachine}/status','App\Http\Controllers\EspressoController@status')->name('status');
    Route::post('{modelEspressoMachine}/add-water','App\Http\Controllers\EspressoController@addWater')->name('add-water');
    Route::post('{modelEspressoMachine}/add-beans','App\Http\Controllers\EspressoController@addBeans')->name('add-beans');
});


