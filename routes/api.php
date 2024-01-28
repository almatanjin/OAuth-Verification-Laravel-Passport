<?php

use App\Http\Controllers\WasherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('washer/register',[WasherController::class, 'store'])->name('washer.register');
Route::post('washer/login',[WasherController::class, 'login'])->name('washer.login');

Route::group( ['prefix' => 'washer','middleware' => ['auth:washer-api'] ],function(){

    Route::get('verification/{id}',[WasherController::class, 'verifyEmail'])->name('washer.verifyEmail');
    Route::get('resend-otp/{id}',[WasherController::class, 'verifyEmail'])->name('washer.verifyEmail');
    Route::post('verification',[WasherController::class, 'verifyOTP'])->name('washer.verifyOTP');
    Route::patch('location', [WasherController::class, 'setLocation'])->name('washer.setLocation');
    Route::post('upload-image/{id}', [WasherController::class, 'uploadImage'])->name('washer.uploadImage');
    Route::get('show-image/{id}', [WasherController::class, 'showImage'])->name('washer.showImage');
    Route::patch('about-service', [WasherController::class, 'aboutService'])->name('washer.aboutService');

}); 

