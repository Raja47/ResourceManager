<?php

namespace App\Http\Controllers\Cms;

use App\Traits\UploadAble;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Contracts\ResourceContract;
use App\Http\Controllers\Controller;
use ImageLib;
use Illuminate\Support\Str;
use Goutte;

class ScrapController extends Controller
{
    use UploadAble;

    protected $resourceRepository;

    public function __construct(ResourceContract $resourceRepository)
    {
        $this->resourceRepository = $resourceRepository;
    }

    public function scraps(Request $request){
        if($request->site == "themeforest"){
            
            $resource= [];
            $crawler = Goutte::request('GET', 'https://themeforest.net/item/bizzark-multipurpose-business-crm-saas-admin/27856652');
            
            $crawler->filter('h1.t-heading.-size-l')->each(function ($node) use(&$resource) {
               $resource['name'] = $node->text();
            });    
            
            $tags = "";
            $crawler->filter('span.meta-attributes__attr-tags')->each(function ($node) use(&$tags) {
                $tags= $node->text();
            });
            $resource['tags'] = $tags;

            $crawler->filter('.item-preview > a')->each(function ($node) use(&$resource) {
                $resource['img'] = $node->children('img')->extract(['src'])[0];
            });

            $desc = ""; 
            $crawler->filter('div.user-html')->each(function ($node) use(&$desc) {
                $desc = $node->text();
            });

            $resource['desc'] = $desc;

            mb_convert_encoding($resource['desc'], 'UTF-8', 'UTF-8');
            return response()->json($resource);

        }elseif($request->site == 'shutterstock' ){
            
            $resource= []; 
            $crawler = Goutte::request('GET', 'https://www.shutterstock.com/image-photo/three-portrait-young-happy-women-red-1826431412');
            
            $crawler->filter('h1.font-headline-base')->each(function ($node) use(&$resource) {
               $resource['name'] = $node->text();  
            });
            if( !isset($resource['name']) ){
            
                $crawler->filter('h1.font-headline-responsive-sm')->each(function ($node) use(&$resource) {
                 
                   $resource['name'] = $node->text();  
                });
            }  

            $tags = "";
            $crawler->filter('div.C_a_03061 a')->each(function ($node) use(&$tags) {
                $tags .= $node->text().",";
            });
            $resource['tags'] = $tags;

            $crawler->filter('.m_l_c4504')->each(function ($node) use(&$resource) {
                $resource['img'] = $node->extract(['src'])[0];
            });
            return response()->json($resource);

        }elseif($request->site == 'istock'){
            
            $resource= []; 
            $crawler = Goutte::request('GET', 'https://www.istockphoto.com/video/dispersed-corona-viruses-with-blue-liquid-background-3d-rendering-gm1204304329-346470601');
            
            $crawler->filter('.image_title h1')->each(function ($node) use(&$resource) {
             
               $resource['name'] = $node->text();  
            });
            
            $tags = "";
            $crawler->filter('.keywords-links')->each(function ($node) use(&$tags) {
                $tags .= $node->text();
            });
            $resource['tags'] = $tags;

            $desc = ""; 
            $crawler->filter('section.description p')->each(function ($node) use(&$desc) {
                $desc = $node->text();
            });
            $resource['desc'] = $desc;

            return response()->json($resource);

        }else{
            
        }
    }



    public function scraps(Request $request){
            
        
    }


    public function shutterScrap(){
        
    
    }

     public function istockScrap(){
        
    
    }


    


    public function upload(Request $request)
    {
        $resource = $this->resourceRepository->findResourceById($request->resource_id);

        if ( $request->has('image')) {
            
            $fileName = Str::random(3).'-'.$resource->id;
            $file  = $fileName.'.'.$request->image->getClientOriginalExtension();

            $originalImage = ImageLib::make($request->image);
            // $originalImage->insert($water_mark_original,'center');
            $originalImage->encode($request->image->getClientOriginalExtension() ,100);
            \Storage::disk('public')->put( 'resources/images/original/'.$file , $originalImage );

            $smallImage = ImageLib::make($request->image)->resize(300,200, function ($constraint) { $constraint->aspectRatio(); } )
              ->encode($request->image->getClientOriginalExtension() , 80);
            \Storage::disk('public')->put( 'resources/images/small/'.$file , $smallImage );


            $resourceImage = new Image([  
                'url'      =>  $file ,
                'imageable_type' => 'App\Models\Resource',
                'imageable_id'   => $resource->id
            ]);
        
            $resource->images()->save($resourceImage);
        }

        return response()->json(['status' => 'Success']);
    }

}
