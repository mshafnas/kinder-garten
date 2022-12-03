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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['prefix'=>'admin'], function(){
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/event', [App\Http\Controllers\DashboardController::class, 'event'])->name('event');
    Route::get('/org', [App\Http\Controllers\DashboardController::class, 'org'])->name('org');
    Route::get('/student', [App\Http\Controllers\DashboardController::class, 'student'])->name('student');
    Route::get('/fee', [App\Http\Controllers\DashboardController::class, 'fee'])->name('fee');
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
    Route::post('/create', [App\Http\Controllers\StudentController::class, 'create'])->name('admin.createStudent');
    Route::post('/delete', [App\Http\Controllers\StudentController::class, 'delete'])->name('admin.deleteStudent');
    Route::post('/get-student', [App\Http\Controllers\StudentController::class, 'getStudent'])->name('admin.getStudent');
    Route::post('/update', [App\Http\Controllers\StudentController::class, 'update'])->name('admin.updateStudent');
});

Route::group(['prefix'=>'admin/fee'], function(){
    Route::get('/get-all', [App\Http\Controllers\FeeController::class, 'getAllFee'])->name('admin.getFees');
    Route::post('/create', [App\Http\Controllers\FeeController::class, 'create'])->name('admin.createFee');
    Route::post('/delete', [App\Http\Controllers\FeeController::class, 'delete'])->name('admin.deleteFee');
    Route::post('/get-fee', [App\Http\Controllers\FeeController::class, 'getFee'])->name('admin.getFee');
    Route::post('/update', [App\Http\Controllers\FeeController::class, 'update'])->name('admin.updateFee');
});
