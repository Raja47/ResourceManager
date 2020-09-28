<?php

use Illuminate\Support\Facades\Route;



Route::group(['prefix'  =>  'admin'], function (){
    
    Route::get('login', 'Cms\LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Cms\LoginController@login')->name('admin.login.post');
    Route::get('logout', 'Cms\LoginController@logout')->name('admin.logout');

    Route::group(['middleware' => ['auth:admin']], function () {

        Route::get('/', function () {
            return view('admin.dashboard.index');
        })->name('admin.dashboard');
       
        Route::group(['prefix' => 'resources'], function () {

           Route::get('/', 'Cms\ResourceController@index')->name('admin.resources.index');
           Route::get('/create', 'Cms\ResourceController@create')->name('admin.resources.create');
           
           Route::post('/store', 'Cms\ResourceController@store')->name('admin.resources.store');
           Route::get('/edit/{id}', 'Cms\ResourceController@edit')->name('admin.resources.edit');
           Route::get('/{id}/delete', 'Cms\ResourceController@delete')->name('admin.resources.delete');
           Route::post('/update', 'Cms\ResourceController@update')->name('admin.resources.update');

           Route::post('images/upload', 'Cms\ImageController@upload')->name('admin.resources.images.upload');
           Route::get('images/{id}/delete', 'Cms\ImageController@delete')->name('admin.resources.images.delete');
           Route::get('images/show', 'Cms\ImageController@show')->name('admin.resources.images.show');

          Route::post('files/upload', 'Cms\FileController@upload')->name('admin.resources.files.upload');
           Route::get('files/{id}/delete', 'Cms\FileController@delete')->name('admin.resources.files.delete');
           Route::get('files/show', 'Cms\FileController@show')->name('admin.resources.files.show');
         

        });

    });
});



/**
 * { Redirect each route to React Js Frontend Site }
 */
Route::view('/{path?}', 'layouts/app');



// Route::get('/home', 'HomeController@index')->name('home');




/// Cms Routes


 // Route::group(['prefix'  =>   'categories'], function() {

        //     Route::get('/', 'Cms\CategoryController@index')->name('admin.categories.index');
        //     Route::get('/create', 'Cms\CategoryController@create')->name('admin.categories.create');
        //     Route::post('/store', 'Cms\CategoryController@store')->name('admin.categories.store');
        //     Route::get('/{id}/edit', 'Cms\CategoryController@edit')->name('admin.categories.edit');
        //     Route::post('/update', 'Cms\CategoryController@update')->name('admin.categories.update');
        //     Route::get('/{id}/delete', 'Cms\CategoryController@delete')->name('admin.categories.delete');

        //     Route::post('banners/upload', 'Cms\CategoryBannerController@upload')->name('admin.categories.banners.upload');
        //     Route::get('banners/{id}/delete', 'Cms\CategoryBannerController@delete')->name('admin.categories.banners.delete');

        // });

