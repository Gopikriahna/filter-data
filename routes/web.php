<?php

use Illuminate\Support\Facades\Route;
use App\Http\controllers\Selectdata;

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

Route::get('/', function () {
    return view('welcome');
 });




Route::get('index',[Selectdata::class,'index']);
Route::post('index',[Selectdata::class,'depart']);
Route::post('company',[Selectdata::class,'companyid']);




Route::get('emp',function () {
    return view('emp');
});
Route::get('joinsdata',[Selectdata::class,'joinsd']);
//  Route::resource('selectdata', Selectdata::class);
//Route::post('country',[Selectdata::class,'country']);
//Route::post('selectdata',[Selectdata::class,'depart']);
//Route::post('country',[Selectdata::class,'country']);
//Route::get('selectdata',[Selectdata::class,'tabledata']);
