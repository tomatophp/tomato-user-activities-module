<?php

use Illuminate\Support\Facades\Route;
use Modules\TomatoUserActivities\App\Http\Controllers\TomatoUserActivitiesController;

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

Route::middleware(['web','auth', 'splade', 'verified'])->name('admin.')->group(function () {
    Route::get('admin/activities', [\Modules\TomatoUserActivities\App\Http\Controllers\ActivityController::class, 'index'])->name('activities.index');
    Route::get('admin/activities/clear', [\Modules\TomatoUserActivities\App\Http\Controllers\ActivityController::class, 'clear'])->name('activities.clear');
    Route::get('admin/activities/api', [\Modules\TomatoUserActivities\App\Http\Controllers\ActivityController::class, 'api'])->name('activities.api');
    Route::get('admin/activities/{model}', [\Modules\TomatoUserActivities\App\Http\Controllers\ActivityController::class, 'show'])->name('activities.show');
    Route::post('admin/activities/{model}', [\Modules\TomatoUserActivities\App\Http\Controllers\ActivityController::class, 'update'])->name('activities.update');
    Route::delete('admin/activities/{model}', [\Modules\TomatoUserActivities\App\Http\Controllers\ActivityController::class, 'destroy'])->name('activities.destroy');
});