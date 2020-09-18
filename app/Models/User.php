<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use Notifiable ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    

    /**
     * { roles that contain user }
     *
     * @return <array of role objects>
     */
    public function roles(){
        return $this->belongsToMany('App\Models\Role');
    }
    
    /**
     * { Resources Created by User }
     *
     * @return     <array>  ( Object of Resources )
     */
    public function resources(){
        return $this->hasMany('App\Model\Resource','creator_id' ,'id');
    }



}
