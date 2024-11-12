<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SubSubCategoryController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('v1')->group(function(){
    Route::prefix('admin')->group(function(){
        Route::prefix('auth')->group(function(){
            Route::name('auth.')->group(function(){
                Route::post('/registration',[AdminController::class, 'registration'])->name('registration');
                Route::post('/login',[AdminController::class, 'login'])->name('login');
                Route::post('/forget-password',[AdminController::class,'forget_password'])->name('forget_password');
                Route::get('/check-reset-token/{token}',[AdminController::class, 'check_token'])->name('check_token');
                Route::post('/reset-password',[AdminController::class, 'resetPassword'])->name('resetPassword');
            });
        });

        // Route::group(['middleware'=>['auth:sanctum']],function(){
            Route::prefix('category')->group(function(){
                Route::name('category.')->group(function(){
                    Route::get('/all',[CategoryController::class, 'all'])->name('all');
                    Route::post('/store',[CategoryController::class, 'store'])->name('store');
                    Route::get('/edit/{id}',[CategoryController::class, 'edit'])->name('edit');
                    Route::post('/update',[CategoryController::class, 'update'])->name('update');
                    Route::delete('/delete/{id}',[CategoryController::class, 'delete'])->name('delete');

                    
                    // View
                    // Route::prefix('view')->group(function(){
                    //     Route::name('view.')->group(function(){
                    //         Route::get('/create',[CategoryController::class, 'create'])->name('create');
                    //         Route::get('/edit/{id}',[CategoryController::class, 'viewEdit'])->name('edit');
                    //         Route::get('/delete/{id}',[CategoryController::class, 'viewDelete'])->name('delete');
                    //     });
                    // });

                });
            });

            // Route::prefix('sub-category')->group(function(){
            //     Route::name('sub-category.')->group(function(){
            //         Route::get('/all',[SubCategoryController::class, 'all'])->name('all');
            //         Route::post('/store',[SubCategoryController::class, 'store'])->name('store');
            //         Route::get('/edit/{id}',[SubCategoryController::class, 'edit'])->name('edit');
            //         Route::post('/update',[SubCategoryController::class, 'update'])->name('update');
            //         Route::delete('/delete/{id}',[SubCategoryController::class, 'delete'])->name('delete');
            //     });
            // });

            // Route::prefix('sub-sub-category')->group(function(){
            //     Route::name('sub-sub-category.')->group(function(){
            //         Route::get('/all',[SubSubCategoryController::class, 'all'])->name('all');
            //         Route::post('/store',[SubSubCategoryController::class, 'store'])->name('store');
            //         Route::get('/edit/{id}',[SubSubCategoryController::class, 'edit'])->name('edit');
            //         Route::post('/update',[SubSubCategoryController::class, 'update'])->name('update');
            //         Route::delete('/delete/{id}',[SubSubCategoryController::class, 'delete'])->name('delete');
            //     });
            // });


            // Tags Api
            Route::prefix('tag')->group(function(){
                Route::name('tag.')->group(function(){
                    Route::get('/all',[TagController::class, 'all'])->name('all');
                    Route::post('/store',[TagController::class, 'store'])->name('store');
                    Route::get('/edit/{id}',[TagController::class, 'edit'])->name('edit');
                    Route::post('/update',[TagController::class, 'update'])->name('update');
                    Route::delete('/delete/{id}',[TagController::class, 'delete'])->name('delete');
                });
            });



            // Brands Api
            Route::prefix('brands')->group(function(){
                Route::name('brands.')->group(function(){
                    Route::get('/all',[BrandController::class, 'all'])->name('all');
                    Route::post('/store',[BrandController::class, 'store'])->name('store');
                    Route::get('/edit/{id}',[BrandController::class, 'edit'])->name('edit');
                    Route::post('/update',[BrandController::class, 'update'])->name('update');
                    Route::delete('/delete/{id}',[BrandController::class, 'delete'])->name('delete');
                });
            });



            // Attributes API
            Route::prefix('attributes')->group(function(){
                Route::name('attributes.')->group(function(){
                    Route::get('/all',[AttributeController::class, 'all'])->name('all');
                    Route::post('/store',[AttributeController::class, 'store'])->name('store');
                    Route::get('/edit/{id}',[AttributeController::class, 'edit'])->name('edit');
                    Route::post('/update',[AttributeController::class, 'update'])->name('update');
                    Route::delete('/delete/{id}',[AttributeController::class, 'delete'])->name('delete');

                    Route::prefix('value')->group(function(){
                        Route::get('/all',[AttributeController::class, 'valueAll'])->name('valueAll');
                        Route::post('/store',[AttributeController::class, 'valueStore'])->name('valueStore');
                        Route::get('/edit/{id}',[AttributeController::class, 'valueEdit'])->name('valueEdit');
                        Route::post('/update',[AttributeController::class, 'valueUpdate'])->name('valueUpdate');
                        Route::delete('/delete/{id}',[AttributeController::class, 'valueDelete'])->name('valueDelete');
                    });
                });
            });



            Route::prefix('product')->group(function(){
                Route::name('product.')->group(function(){
                    Route::get('/all',[ProductController::class, 'all'])->name('all');
                    Route::get('/edit/{id}',[ProductController::class, 'edit'])->name('edit');
                    Route::post('/store',[ProductController::class, 'store'])->name('store');
                    Route::post('/update',[ProductController::class, 'update'])->name('update');
                    Route::delete('/delete/{id}',[ProductController::class, 'delete'])->name('delete');
                    Route::get('/category-lists',[ProductController::class, 'category_lists'])->name('category_all');
                    Route::get('/brand-lists',[ProductController::class, 'brand_lists'])->name('brand_lists');

                });
            });

            
        // });
    });
});
