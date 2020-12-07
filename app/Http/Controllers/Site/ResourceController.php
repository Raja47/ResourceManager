<?php
namespace App\Http\Controllers\Site;

use App\Models\Resource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Searchable\Search;
use Spatie\Searchable\ModelSearchAspect;
use Illuminate\Support\Arr;
use App\Models\ResourceFile;
use App\Models\Image;
use Illuminate\Support\Facades\Config;
use File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
 
class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return response()->json(["data" => "resource site index method","status" => true]);
    }

   
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $resource = Resource::find($id);
        
        if($resource){
            $resource->views = $resource->views + 1;    
            $resource->save();
        }
            

        if($resource){
            return response()->json(
            [
            "data" => [ 
                "resource"  => $resource ,
                "images"    => $resource->images ,
                "files"     => $resource->files ,
                "category"  => $resource->category,
            ],
            "status" => true]
            );    
        }else{
            return response()->json(
            [
            "data" =>null,
            "status" => false]
            );
        }


        
    }

    
    /**
     * { function_description }
     *
     * @param      <type>  $type      The type
     * @param      <type>  $keywords  The keywords
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function suggest($type ,$keywords){
        $searchResults = (new Search())
            ->registerModel(Resource::class, function(ModelSearchAspect $modelSearchAspect) use ($type){
               $modelSearchAspect
                //->addSearchableAttribute('title')
                ->addSearchableAttribute('keywords') // return results for partial matches on usernames
                // ->addExactSearchableAttribute('email') // only return results that exactly e.g email
                ->type($type);  // resourceCategoryId image 1 video 2 
        })->search($keywords)->take(70);
            
            $suggestedKeywords = [];
            foreach($searchResults as $row){
                $suggestedKeywords = array_merge($suggestedKeywords , $row->searchable->keywords);
            }
            $suggestedKeywords = array_unique($suggestedKeywords);
            $temp_array = [];
            
            $count = 1 ;
            foreach ($suggestedKeywords as $value) {
                $arr = [];
                $arr['label'] = $value;    
                $arr["value"] = $value;
                $temp_array[] = $arr;
            }
            
            $suggestedKeywords = $temp_array;
            
            
            
            
            
            
            
            
            
            
            
            return response()->json(["data" =>$searchResults,"status" => true , 'suggestedKeywords'=> $suggestedKeywords ]);
    }

    /**
     * Searches for the first match.
     *
     * @param      <type>  $type      The type
     * @param      <type>  $keywords  The keywords
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function search($type ,$keywords , $page_no , $paginationResults){
            
           
            
        
            
            // $keywords = trim($keywords); 
            // $searchResults = (new Search())
            // ->registerModel(Resource::class, function(ModelSearchAspect $modelSearchAspect) use ($type){
            //     $modelSearchAspect->type($type)
            //     ->addSearchableAttribute('keywords') // return results for partial matches on usernames
            //     // ->addExactSearchableAttribute('email') // only return results that exactly e.g email
            //       // resourceCategoryId image 1 video 2 
            //     ->with(['category','images','files']);
            // })->search($keywords);
            //     $totalResults = $searchResults->count();
            //     $searchResults = $searchResults->slice( ($page_no-1)*$paginationResults , $paginationResults);
            //   return response()->json(["data" => $searchResults , "page_no" => $page_no , 'totalResults' => $totalResults , "status" => true , "searchedFor" => [ "type"=>$type , "keywords"=>$keywords ] ]);
            
           
            $typeArray = ($type == 0)  ? ['1','5','6'] : [$type];
        
            $keywords = trim($keywords);  
            $results = DB::table('resources')
                ->selectRaw("resources.* , resource_categories.title as `category_title`, images.url as uploaded_image_url , resource_files.url as uploaded_file_url ,MATCH(resources.keywords) AGAINST ('$keywords') as `weightage`") 
                ->leftJoin('resource_categories', 'resource_categories.id', '=', 'resources.resource_category_id')
                ->leftJoin('images', function ($join) {
                    $join->on('images.imageable_id', '=', 'resources.id')
                         ->where('images.imageable_type' , 'App\Models\Resource')
                         ->where('images.deleted_at' , null);
                })
                ->leftJoin('resource_files', function ($join) {
                    $join->on('resource_files.resource_id', '=', 'resources.id')
                         ->where('resource_files.deleted_at' , null);
                })
            ->whereRaw("MATCH(resources.keywords) AGAINST ('$keywords')")
            ->whereIn('resources.resource_category_id', $typeArray)
            ->orderByRaw("MATCH(resources.keywords) AGAINST ('$keywords')  Desc")
            ->offset(($page_no-1)*$paginationResults )
            ->limit($paginationResults)
            ->get();
            
            $searchResults =  collect($results)->map(function ($name) {
                                        return collect(['searchable'=>$name]);
                              });
    
            $totalResults = DB::table('resources')
                ->selectRaw("resources.id ,resources.resource_category_id ,resources.keywords  ,MATCH(keywords) AGAINST ('$keywords') as `weightage`") 
            ->whereRaw("MATCH(keywords) AGAINST ('$keywords')")
            ->whereIn('resources.resource_category_id', $typeArray)
            ->count();
            
            return response()->json(["data" => $searchResults , "page_no" => $page_no , 'totalResults' =>  $totalResults , "status" => true , "searchedFor" => [ "type"=>$type , "keywords"=>$keywords ] ]);
            
        
    }

    public function download( $type , $id){
            
            if($type == "image"){

               $image = Image::find($id);
               $resource = $image->imageable;
               if($resource){
                    $resource->downloads = $resource->downloads+1;
                    $resource->save();
               }
               $url = $image->url; 
               return Storage::disk('public')->download("/resources/images/original/".$url);
            }            
            
            $file = ResourceFile::find($id);
            $url = $file->url;
            $resource = $file->resource;
            if($resource){
                $resource->downloads = $resource->downloads+1;
                $resource->save();
            }

            return Storage::disk('public')->download("/resources/files/".$url);
    }
}
