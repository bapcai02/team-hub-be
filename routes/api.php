<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
require __DIR__.'/api/settings.php';
require __DIR__.'/api/departments.php';
require __DIR__.'/api/dashboard.php';
require __DIR__.'/api/calendar.php';
