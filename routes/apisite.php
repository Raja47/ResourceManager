<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get("/resource/search/{type}/{keywords}" , "Site\ResourceController@search" )->name('resourceSearch');
Route::get("/resource/show/{id}" , "Site\ResourceController@show" )->name('site.resource.show');



?>