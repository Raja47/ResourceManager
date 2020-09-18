<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Permission extends Model
{
    use SoftDeletes;

    protected $table = "permissions";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'model' , 'name','permission' ,'status'
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
     * { Roles that contain permissions }
     *
     * @return <array of role objects>
     */
    public function roles(){
        return $this->belongsToMany('App\Models\Role');
    }

}
