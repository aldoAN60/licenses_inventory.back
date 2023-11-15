<?php

use App\Http\Controllers\adminUserController;
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
Route::get('/api/authUser', [adminUserController::class,'getAuthUser'])->name('AuthUser');
Route::get('/api/getArea',[ areaController::class,'getArea'])->name('get.Area');
route::get('/api/getSubArea',[subAreaController::class,'getSubArea'])->name('get.SubArea');
route::get('/api/getCostCenter',[CostCenterController::class, 'getCostCenter'])->name('get.CostCenter');
Route::patch('/api/updateRegistry',[inventoryRegistryController::class,'updateRegistry'])->name('update.Registry');
Route::post('/api/postRegistry',[inventoryRegistryController::class,'createRegistry'])->name('post.Registry');
Route::delete('/api/deleteRegistry/{id_IR}',[inventoryRegistryController::class,'destroyRegistry'])->name('delete.registry');