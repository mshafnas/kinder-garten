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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['prefix'=>'admin'], function(){
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/event', [App\Http\Controllers\DashboardController::class, 'event'])->name('event');
    Route::get('/org', [App\Http\Controllers\DashboardController::class, 'org'])->name('org');
    Route::get('/student', [App\Http\Controllers\DashboardController::class, 'student'])->name('student');
});


Route::group(['prefix'=>'admin/event'], function(){
    Route::get('/event/get-all', [App\Http\Controllers\EventController::class, 'getAllEvents'])->name('admin.getEvent');
    Route::post('/create', [App\Http\Controllers\EventController::class, 'create'])->name('admin.createEvent');
});

Route::group(['prefix'=>'admin/org'], function(){
    Route::get('/get-all', [App\Http\Controllers\OrganizationController::class, 'getAllOrg'])->name('admin.getOrg');
    Route::post('/create', [App\Http\Controllers\OrganizationController::class, 'create'])->name('admin.createOrg');
    Route::post('/delete', [App\Http\Controllers\OrganizationController::class, 'delete'])->name('admin.deleteOrg');
    Route::post('/get-org', [App\Http\Controllers\OrganizationController::class, 'getOrg'])->name('admin.getOrgSingle');
    Route::post('/update', [App\Http\Controllers\OrganizationController::class, 'update'])->name('admin.updateOrg');
});

Route::group(['prefix'=>'admin/student'], function(){
    Route::get('/get-all', [App\Http\Controllers\StudentController::class, 'getAllStudents'])->name('admin.getStudents');
    // Route::post('/create', [App\Http\Controllers\OrganizationController::class, 'create'])->name('admin.createOrg');
    // Route::post('/delete', [App\Http\Controllers\OrganizationController::class, 'delete'])->name('admin.deleteOrg');
    // Route::post('/get-org', [App\Http\Controllers\OrganizationController::class, 'getOrg'])->name('admin.getOrgSingle');
    // Route::post('/update', [App\Http\Controllers\OrganizationController::class, 'update'])->name('admin.updateOrg');
});
