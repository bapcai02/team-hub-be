<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\DepartmentController;
use App\Interfaces\Http\Controllers\DashboardController;

$apiRouteDir = __DIR__ . '/api';

foreach (glob($apiRouteDir . '/*.php') as $file) {
    require $file;
}

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

// Include other route files
require __DIR__.'/api/guests.php';
require __DIR__.'/api/holidays.php';
require __DIR__.'/api/rbac.php';
require __DIR__.'/api/contracts.php';
require __DIR__.'/api/notifications.php';
require __DIR__.'/api/chats.php';

// Dashboard routes
Route::prefix('/dashboard')->middleware(['auth:api'])->group(function () {
    Route::get('/', [DashboardController::class, 'getDashboardData']);
    Route::get('/activities', [DashboardController::class, 'getRecentActivities']);
    Route::get('/charts', [DashboardController::class, 'getChartData']);
});

// Department routes
Route::prefix('/departments')->middleware(['auth:api'])->group(function () {
    Route::get('/', [DepartmentController::class, 'index']);
    Route::get('/{id}', [DepartmentController::class, 'show']);
});
