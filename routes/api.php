<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
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

//blogs
////protected
Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::get("blog", [BlogController::class, "getBlogs"]);
    Route::get("blog/{id}", [BlogController::class, "getBlog"]);
    Route::post("blog", [BlogController::class, "createBlog"]);
    Route::patch("blog", [BlogController::class, "updateBlog"]);
    Route::delete("blog", [BlogController::class, "deleteBlog"]);
});

//users
Route::get("user", [UserController::class, "getUsers"]);
Route::get("user/{id}", [UserController::class, "getUser"]);
Route::post("user/signup", [UserController::class, "signupUser"]);
Route::post("user/login", [UserController::class, "loginUser"]);
