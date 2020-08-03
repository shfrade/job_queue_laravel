<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// default routes
Route::resource('submitter', 'SubmitterController');
Route::resource('processor', 'ProcessorController');
Route::resource('job', 'JobController');

// custom routes
Route::get('processor/nextJob/{processor}', 'ProcessorController@nextJob');
Route::get('processor/finishJob/{processor}', 'ProcessorController@finishJob');
Route::get('report/job/{job?}', 'JobController@detailed');
