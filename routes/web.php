<?php

use App\Http\Livewire\CreateConfigurations;
use App\Http\Livewire\DeliverPeers;
use App\Http\Livewire\DeployChaincode;
use App\Http\Livewire\EnterParameters;
use App\Http\Livewire\Information;
use App\Http\Livewire\Installation;
use App\Http\Livewire\Start;
use App\Http\Livewire\StartNetwork;
use App\Http\Livewire\TestApp;
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

Route::middleware(['web'])->group(function () {
    Route::get('/', Start::class);
    Route::get('/information', Information::class);
    Route::get('/installation', Installation::class);
    Route::get('/enterParameters', EnterParameters::class);
    Route::get('/createConfiguration', CreateConfigurations::class);
    Route::get('/startNetwork', StartNetwork::class);
    Route::get('/deployChaincode', DeployChaincode::class);
    Route::get('/deliverPeers', DeliverPeers::class);
    Route::get('/testApp', TestApp::class);
});

Route::get('/zip-download/{serverIP}/{peerNumber}', 'App\Http\Controllers\ZipController@zipAndDownloadDirectory')->name('download.zip');
Route::post('/zip-upload', 'App\Http\Controllers\ZipController@uploadAndExtract')->name('upload.zip');

//Diese Route löscht alle Session-Daten
Route::get('/sessionClear', function () {
    session()->flush();

    return redirect()->back();
});

//Diese Route wird benötigt, damit die app.js Zugriff auf die Umgebungsvariablen hat
Route::get('/env-variables', function () {
    return response()->json([
        'nodejsPort' => env('NODEJS_PORT'),
        'appURL' => env('APP_URL'),
        // Weitere Environment-Variablen hier hinzufügen
    ]);
});
