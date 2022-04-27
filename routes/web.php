<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\testController;

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index']);

    // ============================= Company-routes-start==================================
    Route::resource('company', CompanyController::class);
    Route::post('company-update', [CompanyController::class, 'update']);
    Route::get('/get-company-data', [CompanyController::class, 'getCompanyData']);
    // ============================= Company-routes-end==================================

    // ============================= employee-routes-start==================================
    Route::resource('employee', EmployeeController::class);
    Route::get('/get-employee-data', [EmployeeController::class, 'getEmployeeData']);
    Route::post('employee-update', [EmployeeController::class, 'update']);
    // ============================= employee-routes-end==================================

});


Auth::routes([
    'reset' => false,
    'verify' => false
]);


