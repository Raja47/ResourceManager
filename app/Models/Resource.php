<?php

namespace App\Models;
    
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Resource extends Model implements Searchable
{
    use SoftDeletes;

    protected $table = "resources";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'title' , 'description','keywords' , 'notes' ,'status' , 'resource_category_id' 
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'keywords' => 'array'
    ];

    /**
     * { Creator of Resource }
     *
     * @return     <collection object of User>
     */
    public function creator(){
        return $this->belongsTo('App\Models\User','creator_id','id');
    }

    /**
     * { category of Resource }
     *
     * @return     <object>  ( category of Resource )
     */
    public function category(){

    	return $this->belongsTo("App\Models\ResourceCategory",'resource_category_id','id');
    }

    /**
     * { files that resource has }
     *
     * @return    <array>  (array of  ResourceFile collection Objects)
     */
    public function files(){
    	return $this->hasMany("App\Models\ResourceFile");	
    }

    /**
     * { resrource Images }
     *
     * @return <array of Image objects Resources has>
     */
    public function images()
    {  
        return $this->morphMany('App\Models\Image', 'imageable');
    }

    /**
     * { filter resources of provided type }
     *
     * @param      <type>  $query  The query
     * @param      <type>  $type   ResourceCatgoryId
     *
     * @return     <Objects>  ( resources of $type )
     */
    public function scopeType($query,$type)
    {
        return $query->where('resource_category_id', $type);
    }

    /**
     * Gets the search result.
     *
     * @return     SearchResult|\  The search result.
     */
    public function getSearchResult(): SearchResult
    {
        $url = "resource?id=".$this->id;
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->title,
            $url
        );
    }

    
}
