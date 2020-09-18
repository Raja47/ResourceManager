<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Image extends Model
{

   use SoftDeletes;	
   protected $table = "images";

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'imageable_id' , 'imageable_type','url' ,'keywords','status'
    ];
   
   /**
    * Description : Morph Eloquent Relations
    * e.g : {imageable_type : "Resource" model
    *		 imageable_id   : "10" id }
    *	--return Resource::find(10)  collection
    * @return    <collection>
    */
   public function imageable()
   {
        return $this->morphTo();
   }

}
