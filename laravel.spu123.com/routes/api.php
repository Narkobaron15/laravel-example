<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;

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

// Route group for categories' CRUD requests
// Located in api middleware with categories prefix,
// permitted requests can be done on such path:
// http://{api_domain}/{middleware}/{prefix}/{request_path}
// http://laravel.spu123.com/api/categories/{request_path}
Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'categories',
    ],
    function () {
        Route::get("/", [CategoryController::class, "index"]);
        Route::post("/create", [CategoryController::class, "create"]);
        Route::get("/{id}", [CategoryController::class, "getById"]);
        Route::post("/edit/{id}", [CategoryController::class, "put"]);
        Route::delete("/{id}", [CategoryController::class, "delete"]);
    }
);

// Route group for products' CRUD requests
Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'products',
    ],
    function () {
        Route::get("/", [ProductController::class, "index"]);
        Route::post("/create", [ProductController::class, "create"]);
        Route::get("/{id}", [ProductController::class, "getById"]);
        Route::post("/edit/{id}", [ProductController::class, "put"]);
        Route::delete("/{id}", [ProductController::class, "delete"]);
    }
);

// Route group for authentication requests
Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'auth',
    ],
    function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/user-profile', [AuthController::class, 'userProfile']);
    }
);
