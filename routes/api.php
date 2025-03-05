<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyAddressController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PatrolController;
use App\Models\PatrolTypes;
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

Route::post('/login', [AuthController::class, 'login']);

Route::get('/not-authorized', function (Request $request) {
    return response()->json([
        "error" => true,
        "message" => "Unauthorized. Please log in to access this resource.",
        "code" => 401
    ], 401);
})->name('not.authorized');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user-logged', [AuthController::class, 'getUser']);
    Route::patch('/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/attendance', [AttendanceController::class, 'getAttendaces']);
    Route::post('/attendance/checkin', [AttendanceController::class, 'checkin']);
    Route::get('/attendance/check', [AttendanceController::class, 'checkStatus']);
    Route::patch('/attendance/checkout', [AttendanceController::class, 'checkout']);
    Route::get('/attendance/get-user-detail', [AttendanceController::class, 'getUserCompanyDetails']);

    Route::get('/company-address', [CompanyAddressController::class, 'index']);

    Route::post('/patrol/create', [PatrolController::class, 'create']);
    Route::get('/patrol-types', [PatrolController::class, 'getPatrolType']);

    Route::get('/leave-types', [LeaveController::class, 'getLeaveType']);
    Route::post('/leave/create', [LeaveController::class, 'create']);
    Route::get('/leaves', [LeaveController::class, 'getLeaves']);

    Route::put('/user/{id}/email', [AuthController::class, 'updateEmail']);

    Route::get('/notices', [NoticeController::class, 'getUserNotices']);
    Route::get('/notice/detail', [NoticeController::class, 'getNoticeDetail']);
    Route::put('/notice-views/read/', [NoticeController::class, 'markAsRead']);

});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login-v2', [AuthController::class, 'login']);
