<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\ManagerController;
use App\Http\Controllers\Api\CallController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/leads', [LeadController::class, 'store']);

Route::post('/leads/{lead}/calls', [CallController::class, 'store']);

Route::get('/managers/{manager}/leads', [ManagerController::class, 'leads']);