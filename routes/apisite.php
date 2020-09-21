<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get("/resource" , "Site\ResourceController@index" )->name('resourceIndex');


?>