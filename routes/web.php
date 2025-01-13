<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Http\Controllers\PaymentController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [FrontController::class, 'index'])->name('front.home');

Route::get('/register', [AuthController::class, 'register'])->name('account.register');
Route::post('/process-register', [AuthController::class, 'processRegister'])->name('account.processRegister');
Route::get('/login', [AuthController::class, 'login'])->name('account.login');

Route::get('/checkout', [PaymentController::class, 'showCheckout'])->name('checkout');
Route::post('/checkout/process', [PaymentController::class, 'processCheckout'])->name('checkout.process');

Route::group(['prefix' => 'admin'], function(){

    Route::group(['middleware' => 'admin.guest'], function(){

        Route::get('/login',[AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate',[AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

        
    });

    Route::group(['middleware' => 'admin.auth'], function(){
        
        Route::get('/dashboard',[HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController::class, 'logout'])->name('admin.logout');
        
        
        //category routes
        Route::get('/categories',[CategoryController::class, 'index'])->name('categories.index');        
        Route::get('/categories/create',[CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories',[CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit',[CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}',[CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}',[CategoryController::class, 'destroy'])->name('categories.delete');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');
        Route::delete('categories/{categoryId}', [CategoryController::class, 'destroy'])->name('categories.delete');



        //temp-images.create
        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');

        Route::get('/getSlug', function (Request $request) {
            $slug = '';
            if (!empty($request->title)) {
                // Proses slug dari title
                
            }

            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getslug');
    });
});