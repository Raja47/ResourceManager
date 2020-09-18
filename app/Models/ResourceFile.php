<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class ResourceFile extends Model
{
    use SoftDeletes;

    
    protected $table = "resource_files";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'url','keywords' ,'status' , 'resource_id' 
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
     * { Resource that File belongsTo  }
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function resource(){
    	return $this->belongsTo("App\Models\Resource");
    }



}
