<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix'  =>  'site'], function (){
    Route::get('/file/download/{type}/{id}' , 'Site\ResourceController@download')->name('site.file.download');
});


Route::group(['prefix'  =>  'admin'], function (){
    Route::get('/', 'Cms\LoginController@showLoginForm')->name('admin.login');
    Route::get('login', 'Cms\LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Cms\LoginController@login')->name('admin.login.post');
    Route::get('logout', 'Cms\LoginController@logout')->name('admin.logout');
    
    Route::get('resource', 'Cms\ResourceController@index')->name('admin.resources.list');
    
    Route::post('/scrap' , 'Cms\ScrapController@scrap')->name('admin.scrapper.scrap');
    Route::get('/scraps' , 'Cms\ScrapController@scraps')->name('admin.scrapper.scraps');
    Route::get('/scrap-download' , 'Cms\ScrapController@scrapDownload')->name('admin.scrapper.scrapDownload');
    Route::group(['middleware' => ['auth:admin']], function () {

        Route::get('/','Cms\HomeController@index' )->name('admin.dashboard');
       
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




