<?php
namespace App\Http\Controllers\Site;

use App\Models\Resource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Searchable\Search;
use Spatie\Searchable\ModelSearchAspect;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return response()->json(["data" => "my love","status" => true]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(Resource $resource)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resource $resource)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource)
    {
        //
    }

    public function search($type ,$keywords){

       $searchResults = (new Search())
            ->registerModel(Resource::class, function(ModelSearchAspect $modelSearchAspect) use ($type){
               $modelSearchAspect
                ->addSearchableAttribute('title')
                ->addSearchableAttribute('keywords') // return results for partial matches on usernames
                // ->addExactSearchableAttribute('email') // only return results that exactly e.g email
                ->type($type)  // resourceCategoryId image 1 video 2 
                // ->has('posts')
                ->with('categories')
                ->with('images');
            })->search($keywords); 
       return response()->json(["data" =>$searchResults,"status" => true]);
    }   
}
