<?php

namespace App\Http\Controllers\Cms;

use App\Traits\UploadAble;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Contracts\ResourceContract;
use App\Http\Controllers\Controller;
use ImageLib;
use Illuminate\Support\Str;
use \Goutte as Goutte;

class ScrapController extends Controller
{
    use UploadAble;

    protected $resourceRepository;

    public function __construct(ResourceContract $resourceRepository)
    {
        $this->resourceRepository = $resourceRepository;
    }

    public function scrap(Request $request){
        if($request->source == "themeforest"){
            
            $resource= [];
            $crawler = Goutte::request('GET', $request->url);
            
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
            $resource['category'] = "theme";
            return response()->json(["status"=> true , 'resource' => $resource]);

        }elseif($request->source == 'shutterstock' ){
            
            $resource= []; 
            $crawler = Goutte::request('GET', $request->url);
            
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
            $tags = substr($tags,0,-1);
            $resource['tags'] = $tags;
            
            $resource['desc'] = $resource['name']; 
            
            $imageKeywordExists = strpos($request->url , "/image-photo/");
            $videoKeywordExists = strpos($request->url , "/video/");
            if($imageKeywordExists){
                $resource["category"] = "image";
            }elseif($videoKeywordExists){
                $resource["category"] = "video";
            }
            
            return response()->json(["status"=> true , 'resource' => $resource]);

        }elseif($request->source == 'istock'){
            
            $resource= []; 
            $crawler = Goutte::request('GET',$request->url );
            
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
            if($desc != ""){
                $resource['desc'] = $desc;
            }else{
                $resource['desc'] = $resource['name'];
            }
            
            $imageKeywordExists = strpos($request->url , "/photo/");
            $videoKeywordExists = strpos($request->url , "/video/");
            if($imageKeywordExists){
                $resource["category"] = "image";
            }elseif($videoKeywordExists){
                $resource["category"] = "video";
            }
            
            return response()->json(["status"=> true , 'resource' => $resource]);

        }else{
           return response()->json(["status"=> false]);
        }
    }


    

}
