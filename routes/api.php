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
Route::prefix('espresso-machine')->group(function(){
    Route::get('/','App\Http\Controllers\EspressoMachineController@index');
    Route::post('config','App\Http\Controllers\EspressoMachineController@config');
    Route::post('{modelEspressoMachine}/config','App\Http\Controllers\EspressoMachineController@reconfig');
    Route::delete('{modelEspressoMachine}','App\Http\Controllers\EspressoMachineController@delete');
    Route::get('{modelEspressoMachine}/one','App\Http\Controllers\EspressoController@one');
    Route::get('{modelEspressoMachine}/double','App\Http\Controllers\EspressoController@double');
    Route::get('{modelEspressoMachine}/status','App\Http\Controllers\EspressoController@status');
    Route::post('{modelEspressoMachine}/add-water','App\Http\Controllers\EspressoController@addWater');
    Route::post('{modelEspressoMachine}/add-beans','App\Http\Controllers\EspressoController@addBeans');
});


