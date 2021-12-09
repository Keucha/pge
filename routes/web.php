<?php

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

Route::get('/', function () {
    return redirect(route('login'));
});

Route::get('/adm/login',[App\Http\Controllers\Admin::class, 'adminloginview'])->name('adminlogin');

Route::post('loadinitdatalocations',[App\Http\Controllers\Admin::class, 'loadlocationsatinit']);

Route::get('/users/list',[App\Http\Controllers\HomeController::class, 'userlistingview']);

Route::get('useremail',[App\Http\Controllers\Admin::class,'sentemailview'])->name('useremail');

Route::get('averified/{id}',[App\Http\Controllers\Admin::class,'accountverified']);

Route::get('/users/datatable',[App\Http\Controllers\HomeController::class,'userlistdatatable'])->name('userdatatable');

Route::post('/postdata',[App\Http\Controllers\Admin::class,'googledatastore']);

Route::post('/getalllocations',[App\Http\Controllers\Admin::class,'getalllocationsbyuserid']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('user/location/{id}',[App\Http\Controllers\HomeController::class,'userlocationlist']);

Route::get('/users/datatabletwo',[App\Http\Controllers\HomeController::class,'userlistdatatabletwo'])->name('userdatatabletwo');

Route::get('/mapview',[App\Http\Controllers\HomeController::class, 'mapview'])->name('mapview');

Route::post('/saveUser',[App\Http\Controllers\Admin::class, 'saveUser'])->name('saveUser');
