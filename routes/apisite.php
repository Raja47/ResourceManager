<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get("/admin" , "Site\ResourceController@index" )->name('resourceIndex');


?>