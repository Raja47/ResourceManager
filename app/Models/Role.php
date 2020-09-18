<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Role extends Model
{	
	use SoftDeletes;
    
    protected $table = "roles";
    
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','status'
    ];

    /**
     * { cast datatypes  }
     *
     * @var  array
     */
    protected $casts = [
    ];

    /**
     * { users that contain role}
     *
     * @return <array of user objects>  
     */
    public function users(){
    	return $this->belongsToMany('App\Models\Role');
    }

     /**
     * { permission that role contains }
     *
     * @return <array of permission objects>
     */
    public function permissions(){
        return $this->belongsToMany('App\Models\Permission');
    }
}
