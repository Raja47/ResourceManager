<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class ResourceCategory extends Model
{
    use SoftDeletes;

    protected $table = "resource_categories";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'title' , 'description','keywords' ,'status' , 'resource_category_id' 
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // e.g 'email_verified_at' => 'datetime',
    ];

    /**
     * { Category Resrouces }
     *
     * @return     <array>  ( objects of resources )
     */
    public function resources(){

    	return $this->hasMany('App\Models\Resource','resource_category_id','id');
    }
}
