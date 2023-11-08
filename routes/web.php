<?php

use App\Http\Controllers\areaController;
use App\Http\Controllers\CostCenterController;
use App\Http\Controllers\inventoryRegistryController;
use App\Http\Controllers\licensesController;
use App\Http\Controllers\subAreaController;
use App\Http\Controllers\userController;

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});
Route::get('/api/usuarios',[userController::class,'index'])->name('index');
Route::get('/api/total-Licenses', [inventoryRegistryController::class,'getTotalRegistry'])->name('totalLicenses');
Route::get('/api/registry', [inventoryRegistryController::class,'registry'])->name('registry');
Route::get('/api/authUser', [userController::class,'getAuthUser'])->name('AuthUser');
Route::get('/api/getArea',[ areaController::class,'getArea'])->name('getArea');
route::get('/api/getSubArea',[subAreaController::class,'getSubArea'])->name('getSubArea');
route::get('/api/getCostCenter',[CostCenterController::class, 'getCostCenter'])->name('getCostCenter');
Route::patch('/api/updateRegistry',[inventoryRegistryController::class,'updateRegistry'])->name('updateRegistry');
Route::post('/api/postRegistry',[inventoryRegistryController::class,'createRegistry'])->name('postRegistry');