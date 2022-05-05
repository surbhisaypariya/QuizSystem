<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\QuestionController;
use App\Http\Controllers\admin\SubjectController;

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

//  user notification
Route::get('password-setview/{email}',[App\Http\Controllers\admin\UserController::class,'password_setview'])->name('password_setview')->middleware('guest');
Route::get('password-set/{email}',[App\Http\Controllers\admin\UserController::class,'password_set'])->name('password_set');
Route::post('password-set-user',[App\Http\Controllers\admin\UserController::class,'password_set_user'])->name('password_set_user');

Route::group(['middleware'=>['auth']],function(){
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::controller(UserController::class)->group(function(){
        Route::resource('/user',UserController::class);
        Route::post('ajax_fetchuser','ajax_fetchuser')->name('ajax_fetchuser');
        Route::post('change-multiple-status','change_multiple_status')->name('change_multiple_status');
    });
    
    Route::controller(SubjectController::class)->group(function(){
        Route::resource('/subject',SubjectController::class);
        Route::post('ajax_fetchsubject','ajax_fetchsubject')->name('ajax_fetchsubject');
    });
    
    Route::controller(QuestionController::class)->group(function(){
        Route::resource('/question',QuestionController::class);
        Route::post('ajax_fetchquestion','ajax_fetchquestion')->name('ajax_fetchquestion');
    });
});
