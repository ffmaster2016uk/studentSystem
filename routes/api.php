<?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\StudentController;

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

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::group(['prefix' => 'students', 'middleware' => ['auth:sanctum']], function () {
        Route::post('/search', StudentController::class . '@search')->name('students-search');
        Route::get('/{id?}', StudentController::class . '@view')->name('students-view');
        Route::put('/store', StudentController::class . '@store')->name('students-store');
        Route::patch('/update', StudentController::class . '@update')->name('students-update');

    });
