<?php

namespace App\Http\Controllers\Cms;

use App\Traits\UploadAble;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ImageLib;
use Illuminate\Support\Str;
use \Goutte as Goutte;
use App\Models\Resource;

class ScrapController extends Controller
{
    use UploadAble;
    
    protected $token;

    public function __construct()
    {
        $this->token = '1/eyJjbGllbnRfaWQiOiI0ZGVlMi04Zjc3NS1kZDRjNi00ZTU2MS02ZTY0NS0xYWEwZiIsInJlYWxtIjoiY3VzdG9tZXIiLCJzY29wZSI6InVzZXIudmlldyB1c2VyLmVtYWlsIHVzZXIuYWRkcmVzcyB1c2VyLmVkaXQgb3JnYW5pemF0aW9uLnZpZXcgb3JnYW5pemF0aW9uLmFkZHJlc3MgY29sbGVjdGlvbnMudmlldyBjb2xsZWN0aW9ucy5lZGl0IGxpY2Vuc2VzLnZpZXcgbGljZW5zZXMuY3JlYXRlIG1lZGlhLnVwbG9hZCBtZWRpYS5zdWJtaXQgbWVkaWEuZWRpdCBwdXJjaGFzZXMudmlldyBwdXJjaGFzZXMuY3JlYXRlIiwidXR2IjoiWFVjMiIsInVzZXJuYW1lIjoiYWRtaW4yNzU1ODczIiwidXNlcl9pZCI6MTkzODYzOTg4LCJvcmdhbml6YXRpb25faWQiOm51bGwsIm9yZ2FuaXphdGlvbl91c2VyX2lkIjpudWxsLCJwYXJlbnRfb3JnYW5pemF0aW9uX2lkcyI6W10sImN1c3RvbWVyX2lkIjoxOTM4NjM5ODgsImV4cCI6MTYwODU1MDg1NH0.bkc5vRFN5xa-oOCFLhA1af8B-5U-1qRw-nKZE-NK4QtMKxDnX4iWDconomSAU85BJF65bl9PjKy_atPYQegWvQ';
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
                $resource['image'] = $node->children('img')->extract(['src'])[0];
            });
            
            $desc = "";
            $crawler->filter('div.user-html')->each(function ($node) use(&$desc) {
                $desc = $node->text();
            });

            $resource['desc'] = strlen($desc) > 300 ? substr($desc,0,300) : $desc ;
            
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
            
            $imagePhotoKeywordExists = strpos($request->url , "/image-photo/");
            $imageVectorKeywordExists = strpos($request->url , "/image-vector/");
            $imageIllustrationKeywordExists = strpos($request->url , "/image-illustration/");
            $videoKeywordExists = strpos($request->url , "/video/");
            
            if($imageKeywordExists){
                $resource["category"] = "image-photo";
            }elseif($imageVectorKeywordExists){
                $resource["category"] = "image-vector";
            }elseif($imageIllustrationKeywordExists){
                $resource["category"] = "image-illustration";
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
            
            $imagePhotoKeywordExists = strpos($request->url , "/photo/");
            $imageVectorKeywordExists = strpos($request->url , "/vector/");
            $videoKeywordExists = strpos($request->url , "/video/");
            
            if( $imagePhotoKeywordExists ){
                $resource["category"] = "image-photo";
            }elseif($imageVectorKeywordExists){
                $resource["category"] = "image-vector";
            }elseif($videoKeywordExists){
                $resource["category"] = "video";
            }
            
            return response()->json(["status"=> true , 'resource' => $resource]);
            
        }else{
           return response()->json(["status"=> false]);
        }
    }


    public function getPaginatedImages(){
        
            for($page_number = 33 ;$page_number <=  34 ;$page_number++){    
                
                // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, 'https://www.shutterstock.com/studioapi/user/licenses?filter%5Blicense_names%5D=standard%2Cenhanced%2Ceditorial&filter%5Bmedia_type%5D=photo%2Cimage&page%5Bsize%5D=96&include=media-item%2Cproduct&page%5Bnumber%5D='.$page_number.'&filter%5Blicensee_type%5D=all&sort=newest');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                
                $headers = array();
                $headers[] = 'Authority: www.shutterstock.com';
                $headers[] = 'X-End-User-Ua: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36';
                $headers[] = 'Dnt: 1';
                $headers[] = 'X-End-User-Ip: 124.29.206.134';
                $headers[] = 'Authorization: Bearer 1/eyJjbGllbnRfaWQiOiI0ZGVlMi04Zjc3NS1kZDRjNi00ZTU2MS02ZTY0NS0xYWEwZiIsInJlYWxtIjoiY3VzdG9tZXIiLCJzY29wZSI6InVzZXIudmlldyB1c2VyLmVtYWlsIHVzZXIuYWRkcmVzcyB1c2VyLmVkaXQgb3JnYW5pemF0aW9uLnZpZXcgb3JnYW5pemF0aW9uLmFkZHJlc3MgY29sbGVjdGlvbnMudmlldyBjb2xsZWN0aW9ucy5lZGl0IGxpY2Vuc2VzLnZpZXcgbGljZW5zZXMuY3JlYXRlIG1lZGlhLnVwbG9hZCBtZWRpYS5zdWJtaXQgbWVkaWEuZWRpdCBwdXJjaGFzZXMudmlldyBwdXJjaGFzZXMuY3JlYXRlIiwidXR2IjoianZCeCIsInVzZXJuYW1lIjoicnlkZXJ0cmVhYzcxIiwidXNlcl9pZCI6MjU1MDA1NzcxLCJvcmdhbml6YXRpb25faWQiOm51bGwsIm9yZ2FuaXphdGlvbl91c2VyX2lkIjpudWxsLCJwYXJlbnRfb3JnYW5pemF0aW9uX2lkcyI6W10sImN1c3RvbWVyX2lkIjoyNTUwMDU3NzEsImV4cCI6MTYwODYxNTk1Nn0.R_xRcsxwiGWPV1FuMVLEpPhQlvWk4juwjS5Sfcgo_LFgczeMxHXi_2lBSLbyHFfZvgBM_DldsnKjueG5ImF-Kw';
                $headers[] = 'X-Shutterstock-Features: tokencide';
                $headers[] = 'X-Shutterstock-User-Token: eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzc3RrX2RhdGEiOnsiaWQiOjI1NTAwNTc3MSwibG9jYWxlcyI6WyJlbiJdLCJjcmVhdGVfdGltZSI6IjIwMjAtMDEtMDJUMTc6MDI6MDQuMDAwWiJ9LCJzc3RrX3ByaXZfZGF0YSI6ImE4NWU2Mjk5ODZmYjg1YmYxNDI1MjllOGFlM2FhZGQwLjc5MmRhYjE3MDVkY2NlN2IzZjExOTEwNzdmN2JkZTAwZDBjZTk4YmJjMjg2YWE0NzRjOTc2ZjVjMGFmODY3NDlmOGJmYjA5MDQ1N2Y5ZTRlOGM0M2RlZjkzOGY1YmJmODI1NTkzY2E1NzAyZmQ2NjEyN2RkYWU0MWU3ZTBiMDU1MDcxOTE0ZTE4ZTFhOTJlNTUwMGM1MjQ0YjVjMDBjZDMiLCJpYXQiOjE2MDg2MTIzNTYsImlzcyI6ImFjY291bnRzIiwic3ViIjoidXNlci4yNTUwMDU3NzEifQ.rEwUjxEKGI5CSq53ryJUTccU0_MOk7p5Klpe0qGAOB8QOpMiQsp9LTflRnu1-FzXmTj85SpeFvLlV7fCeaINAi3B3TKfgHqcdhgSiMqDCM0ftZZNHf2lnP-1hGVrfVnZzWTp9B4uvzqwKlbdJZJGfXlTZoSvOQOB1tQXOrZFgBEXN6aVaj3pxcbUsccDi-9EYgSdzfjTC53sFriohBZ67RO39-Dd0O-N-tnblrzRqthrMSfdQjlsDkTDiOEejL-AQo9osyAWtzMtWALX9WliwU-IIy6Dt1xs0TV2JDrxlkVGSfX8t8DQgcYh0KEO5ZWXRVYuetDbvXMso25C0HvRhPqW6jQ-HyORKTLsIMShNM2LhSpBZMz4ZbGQW1EwijaznG2lQBbrM5Jd1twIouqga0YODZ1wxImyvn2cLyyIsBEn5ehQAR13-W3rkDFirPsTVTHx-USa0oTW50EFgYjus2wyP4DHUfS2BX6HgJcOrcNcbS_a7fVdY-hb7wV4o9R95LG5qzmtIVghyDQxqxKfojxJ43FjoW5400HjMdUJyaUJ7Po96v5sJvWEiT8bvOmFtKmG42v7tvr6uw-0jofWoyVO6QP4pXLo8KqAG5Ei3dNYxotDNpy0Tqv1hzEXJpZh_CPWL8VUvNYQ-pDMuSlXAzm3riVadO6dqfIV1j6A1pg';
                $headers[] = 'X-Shutterstock-Search-Id: null';
                $headers[] = 'X-End-User-Country: PK';
                $headers[] = 'X-Request-Id: 8f6c0380-9e0b-48ed-8bc7-eaf94d8e4ae3';
                $headers[] = 'X-End-User-Visit-Id: 69663977899';
                $headers[] = 'X-Shutterstock-Site-Section: sstk/licenses/image';
                $headers[] = 'X-End-App-Version: 0.1134.6';
                $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36';
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'X-End-App-Name: studio-web';
                $headers[] = 'Accept: application/json';
                $headers[] = 'X-End-User-Id: 255005771';
                $headers[] = 'X-End-User-Visitor-Id: 63848983170';
                $headers[] = 'Sec-Fetch-Site: same-origin';
                $headers[] = 'Sec-Fetch-Mode: cors';
                $headers[] = 'Sec-Fetch-Dest: empty';
                $headers[] = 'Referer: https://www.shutterstock.com/licenses/image';
                $headers[] = 'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8';
                $headers[] = 'Cookie: visitor_id=63848983170; did=5745bd6b-3bc0-475b-a042-992e92bdc924; connect.sid=s%3ANEsM_pPmq6BruyAh-3KgPk6O9-vWnJ0J.mSCdhXOKBvYXCV3eQ%2FLbtbxnTe6gJw1zHbgn4%2FRy7nI; __ssid=2df00e6272821102261db2c18419ea5; optimizelyEndUserId=oeu1606457031873r0.113129512866039; _gcl_au=1.1.1393381827.1606457034; C3UID-924=4120151631606457035; C3UID=4120151631606457035; sstk_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; IR_gbd=shutterstock.com; _ts_yjad=1606457049145; __qca=P0-661513937-1606457048890; _ga=GA1.2.2075455506.1606457070; ajs_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; _cs_ex=1596812801; _cs_c=1; _ym_uid=1606709788437414803; _ym_d=1606709788; usi_analytics=a_3kb80s_1606709789_5698; _biz_uid=2a77cc1f179742859ad0873b14592ad7; _biz_flagsA=%7B%22Version%22%3A1%2C%22ViewThrough%22%3A%221%22%2C%22XDomain%22%3A%221%22%7D; ei_client_id=5fd84cf4806cad0011cfa720; driftt_aid=2f6bfc96-a87e-46a4-a17d-5ae9452a24da; DFTT_END_USER_PREV_BOOTSTRAPPED=true; _biz_nA=2; _biz_pendingA=%5B%5D; search=/search/gandhi?; footage_search_tracking_id=540c7f42-a05e-4bd9-b1b8-435ed63f06eb; locale=en-GB; usi_existing_customer=1; _gid=GA1.2.1166992505.1608543432; _ym_isad=1; visit_id=69663977899; _actts=1606457047.1608549674.1608612225; sstk.sid=s%3ARtUJeOA9QQJtIH0zDdnggwpkogPi_Rp8.lW1TOtijoFbl2f%2Fo8LQW%2BQhdwKVnwVTgczIlsaSLlWs; _actvc=17; _cs_mk=0.47382762525168154_1608612226538; AMP_TOKEN=%24NOT_FOUND; accts_customer=rydertreac71; accts_customer_sso1=255005771-undefined; ajs_user_id=%22255005771%22; ajs_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; IR_1305=1608612422701%7C0%7C1608612231831%7C%7C; IR_PI=5d219c2b-3076-11eb-8648-42010a24661c%7C1608698822701; _uetsid=1a0a2a60437011eb97bf0727594fa669; _uetvid=5d29a3a0307611eb926f21f42ecb5121; _4c_=jVLBbuMgEP2ViHNJDAYMOW5PK%2B2euvcKA6lRHGMBjpuN8u87JG5SVXuoL2Ye894wM%2B%2BM5s4NaEtEJQWhijeciSe0d6eEtmcUvS2%2FI9oiJYxxDW%2FxTmiJWU0bLLmhWNRaUWGV4IahJ%2FRetAijdV0xKbm8PCEzLhpnNMUepLqcx7TdbOZ5XqduytnFlIPZr004bHpv3JBc2viDfnOg6IZSfowWzinlffLZAXD01oUCTW0y0Y%2FZh%2BHPaSxXL5%2BgtMKrhler32HIXX8CwpRcfMk6T9AhMhNUPrgIuAnTkOOp1NpDqIcwnA5hSj9hBIi4xnJNKGaydN%2B2BmtuGaaNJBT63FnWXjVseQBRa0LWFID8F8K6quA4xmAnk1%2Fz7ZGza1fJlkrWHaHn19nb3F3JtHqgnfNvXS5wJa%2FwGEsAp9kPNswPmmTygd5pipWttDHM0DbEz12EfleyATSUCfzyw%2FQOQXQ7F%2BM1p0z1NuSv21luwB5fLkt%2FZU21gFMfjO4LHZwFBGfKIiD8QZ%2FRZbEI2E2pmnMwEkwpgy2kYFX5Ljepq2Nq8Z30W3cY5LEb7lxKP3OrRjbVf7hH%2F2HwtpWM0p3GioO3WWMsVlUlsNJaOW1ES4VBD0nCGngOrRdJIu%2BP7xdF8kimQtSUcPGRzJb6l8s%2F; OptanonConsent=isIABGlobal=false&datestamp=Tue+Dec+22+2020+09%3A56%3A10+GMT%2B0500+(Pakistan+Standard+Time)&version=6.10.0&hosts=&consentId=a6b045c0-2232-4a78-93be-dd3d9115dcce&interactionCount=1&landingPath=NotLandingPage&groups=C0003%3A1%2CC0001%3A1%2CC0002%3A1%2CC0005%3A1%2CC0004%3A1%2CSPD_BG%3A1%2CC0007%3A1&AwaitingReconsent=false; _actcc=48.18.306.104; _gat_UA-32034-16=1; _actmu=ee8b9134-5946-4f13-9e56-0844fe5f9346; _actms=41bad9f3-c23a-4e04-8669-4b1979935dda';
                $headers[] = 'If-None-Match: W/\"479bc-r8hNbU4gUiYGvvgZ+plB956Xrzg\"';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                
                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
                
                // print_r(  json_decode($result,true) );
                // die();
                $rows = json_decode($result,true)['included'];
                
                foreach($rows as $row){
                    
                    if($row['type'] == 'products' || Resource::where('sourceable_id', $row['id'])->get()->first() ){
                        continue;
                    }
                    
                    $resource = new Resource();
                    $record = [];
                    $resource->sourceable_id   = $row['id'];
                    $resource->resource_category_id =  strpos( $row['attributes']['link'] , 'image-photo' ) ?  '1' : (  strpos( $row['attributes']['link'] , 'image-vector' ) ? '5' : ( strpos( $row['attributes']['link'] , 'image-illustration') ? '6' : '9' )   );
                    $resource->sourceable_type = 'shutterstock';
                    $resource->sourceable_format = strpos( $row['attributes']['link'] , 'image-photo' ) ?  'jpg' : (  strpos( $row['attributes']['link'] , 'image-vector' ) ? 'eps' : ( strpos( $row['attributes']['link'] , 'image-illustration') ? 'jpg' : 'none' )   );
                    $resource->title          = $row['attributes']['title'] ? $row['attributes']['title'] : "" ;
                    $resource->description      = $row['attributes']['description'] ? $row['attributes']['description'] : "";
                    $resource->keywords         = $row['attributes']['keywords'] ? $row['attributes']['keywords'] : [];
                    $resource->image            = $row['attributes']['src'] ? $row['attributes']['src'] : "";
                    $resource->sourceable_link  = $row['attributes']['link'] ? $row['attributes']['link'] : "";
                    $resource->sourceable_account  = "ryder.treac@gmail.com";
                    $resource->save();                    
                }   
            } 
    }
    
    public function getVideos(){
        
        
        // $resources = Resource::all();
        // foreach( $resources as $resource ){
        //     $keywords  = $resource->keywords;
        //     $keywords[] = "All";
        //     $resource->keywords = $keywords;
        //     $resource->save();
        // }
        
          // $recordFile = fopen(asset("/shutterstock-resources.json",'w'));
          
            // for($page_number = 1 ;$page_number <= 1 ;$page_number++){    
                
               
            //     $ch = curl_init();
                
            //     curl_setopt($ch, CURLOPT_URL, 'https://www.shutterstock.com/studioapi/user/licenses?filter%5Bmedia_type%5D=video&page%5Bsize%5D=96&include=media-item%2Cproduct&page%5Bnumber%5D=1&filter%5Blicensee_type%5D=all&sort=newest');
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                
            //     curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                
            //     $headers = array();
            //     $headers[] = 'Authority: www.shutterstock.com';
            //     $headers[] = 'X-End-User-Ua: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36';
            //     $headers[] = 'Dnt: 1';
            //     $headers[] = 'X-End-User-Ip: 124.29.206.134';
            //     $headers[] = 'Authorization: Bearer 1/eyJjbGllbnRfaWQiOiI0ZGVlMi04Zjc3NS1kZDRjNi00ZTU2MS02ZTY0NS0xYWEwZiIsInJlYWxtIjoiY3VzdG9tZXIiLCJzY29wZSI6InVzZXIudmlldyB1c2VyLmVtYWlsIHVzZXIuYWRkcmVzcyB1c2VyLmVkaXQgb3JnYW5pemF0aW9uLnZpZXcgb3JnYW5pemF0aW9uLmFkZHJlc3MgY29sbGVjdGlvbnMudmlldyBjb2xsZWN0aW9ucy5lZGl0IGxpY2Vuc2VzLnZpZXcgbGljZW5zZXMuY3JlYXRlIG1lZGlhLnVwbG9hZCBtZWRpYS5zdWJtaXQgbWVkaWEuZWRpdCBwdXJjaGFzZXMudmlldyBwdXJjaGFzZXMuY3JlYXRlIiwidXR2IjoiWFVjMiIsInVzZXJuYW1lIjoiYWRtaW4yNzU1ODczIiwidXNlcl9pZCI6MTkzODYzOTg4LCJvcmdhbml6YXRpb25faWQiOm51bGwsIm9yZ2FuaXphdGlvbl91c2VyX2lkIjpudWxsLCJwYXJlbnRfb3JnYW5pemF0aW9uX2lkcyI6W10sImN1c3RvbWVyX2lkIjoxOTM4NjM5ODgsImV4cCI6MTYwODYyNjc0NH0.lzt6-BtxI7xW5jOvvUcQ3_o87snJdg5yjl_9QFVc9WSPtHrK4u7QpbeqJK5U0K6S2JFJnqXifikKa8Cg17Oupg';
            //     $headers[] = 'X-Shutterstock-Features: tokencide';
            //     $headers[] = 'X-Shutterstock-User-Token: eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzc3RrX2RhdGEiOnsiaWQiOjE5Mzg2Mzk4OCwibG9jYWxlcyI6WyJlbiIsInpoLUhhbnQiXSwiY3JlYXRlX3RpbWUiOiIyMDE4LTAyLTE5VDIxOjEyOjE3LjAwMFoifSwic3N0a19wcml2X2RhdGEiOiJhNDZjYTNiYzdjYzZmN2Y0Njc3ZTkzM2U3YTAyMWJkZC45NTQzYzNlN2QyNjZlNWVlMjEwZGFjMzVlMGUxNzc4YmZlMmI2YzZhMDQ2ZTc2M2M1NWJhNjc5YjExYmUyOTk0OGIxM2RlZGI2ZmQ3OTI2YWU2YjQ0ZmRhZjFlNGQ5Yjk4NTA5ODBlZDE0ZDk2ZjVjNjdkMDRjZTYxMTYzZmZiZGFlMThiMWNiM2JkOWYwNmRmNzRhY2I1M2IxMzA2NjkwNzNlYzYzY2UwZjdlNmE2NjAyNTBkMTkzN2RkYmY2MGUiLCJpYXQiOjE2MDg2MjMxNDQsImlzcyI6ImFjY291bnRzIiwic3ViIjoidXNlci4xOTM4NjM5ODgifQ.nLEWUpT76aOnbWyHFDK-ytZ2iGtUbgFiraSNQpf0msl2caosoKHQPgbp8PB5rP_zJ7ZO-QKnpboBKpSDkTT_0WjYTOPS8iUKyKvypXMp6ZN3d8sYQ_fmmDV4lghQrTTcSC_VWuQWBcJOwJoeQA0nVoTFwl76oTsmQ01NVmnB623pQe6lStFMDK_7DdJcuPu-UQwaKPkZwUBEjYUL1B8h0VVVWzlB4SWGNqwt7Vntovp_2qhbVvRv9Sd-DVrrHkYiUG_Wr4lSQGbrmuDctg_yFDUyQIPUJ1f9cbnDLxEbT_bZoTyBos_VFzctYJLZniIKUUu5MTJAnVkVidugbOs574NN4csTqQP2b-2Qy9A_ce-TFkCPfkVDqIvktTl6QkUOCqzbGYWnexo5E5kU3fHxwWasAwcHYKub4mTPKhFRQ5fZqofMjKEfq6CDBQe5L2SYLXPhkA9U011ZtHIIBSP6jMV97_1ojOZDtOCNl0hzxRAAnbyKFkiGwZu6UvJO_UqDV4iLWAEiRMN07FkH3duPODp9aUFagNesfLX8x3eMMcj0mP2hkVuooRR866jMm7HrEJa6hjiFJQiw83q5D4q0O8O82Zim4jQvKxhqdP7NjvtLVMxm7gBL8cWbEiM7TQ5Xd7YS-P7pBe53qqezSwF5MKxKURG8NV6a-N8Zxqr_lYw';
            //     $headers[] = 'X-Shutterstock-Search-Id: null';
            //     $headers[] = 'X-End-User-Country: PK';
            //     $headers[] = 'X-Request-Id: 2436a3be-b044-4e19-bc6d-d4a5f49320ed';
            //     $headers[] = 'X-End-User-Visit-Id: 69664821628';
            //     $headers[] = 'X-Shutterstock-Site-Section: sstk/licenses/video';
            //     $headers[] = 'X-End-App-Version: 0.1134.6';
            //     $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36';
            //     $headers[] = 'Content-Type: application/json';
            //     $headers[] = 'X-End-App-Name: studio-web';
            //     $headers[] = 'Accept: application/json';
            //     $headers[] = 'X-End-User-Id: 193863988';
            //     $headers[] = 'X-End-User-Visitor-Id: 63848983170';
            //     $headers[] = 'Sec-Fetch-Site: same-origin';
            //     $headers[] = 'Sec-Fetch-Mode: cors';
            //     $headers[] = 'Sec-Fetch-Dest: empty';
            //     $headers[] = 'Referer: https://www.shutterstock.com/licenses/video';
            //     $headers[] = 'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8';
            //     $headers[] = 'Cookie: visitor_id=63848983170; did=5745bd6b-3bc0-475b-a042-992e92bdc924; connect.sid=s%3ANEsM_pPmq6BruyAh-3KgPk6O9-vWnJ0J.mSCdhXOKBvYXCV3eQ%2FLbtbxnTe6gJw1zHbgn4%2FRy7nI; __ssid=2df00e6272821102261db2c18419ea5; optimizelyEndUserId=oeu1606457031873r0.113129512866039; _gcl_au=1.1.1393381827.1606457034; C3UID-924=4120151631606457035; C3UID=4120151631606457035; sstk_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; IR_gbd=shutterstock.com; _ts_yjad=1606457049145; __qca=P0-661513937-1606457048890; _ga=GA1.2.2075455506.1606457070; ajs_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; _cs_ex=1596812801; _cs_c=1; _ym_uid=1606709788437414803; _ym_d=1606709788; usi_analytics=a_3kb80s_1606709789_5698; _biz_uid=2a77cc1f179742859ad0873b14592ad7; _biz_flagsA=%7B%22Version%22%3A1%2C%22ViewThrough%22%3A%221%22%2C%22XDomain%22%3A%221%22%7D; ei_client_id=5fd84cf4806cad0011cfa720; driftt_aid=2f6bfc96-a87e-46a4-a17d-5ae9452a24da; DFTT_END_USER_PREV_BOOTSTRAPPED=true; _biz_nA=2; _biz_pendingA=%5B%5D; search=/search/gandhi?; footage_search_tracking_id=540c7f42-a05e-4bd9-b1b8-435ed63f06eb; locale=en-GB; usi_existing_customer=1; _gid=GA1.2.1166992505.1608543432; _ym_isad=1; _actts=1606457047.1608612225.1608617815; _actvc=18; AMP_TOKEN=%24NOT_FOUND; _cs_mk=0.555850021624168_1608623121188; lihptb=0; sstk.sid=s%3AWKrS3WGSSBSsUeuVT4kDWVNFFuM52MFG.Pzskgb%2BZHSJn5DVkdtBWbS2oK5yZqQvnqRMVfoFK8pE; visit_id=69664821628; accts_customer=admin2755873; accts_customer_sso1=193863988-undefined; OptanonConsent=isIABGlobal=false&datestamp=Tue+Dec+22+2020+12%3A45%3A50+GMT%2B0500+(Pakistan+Standard+Time)&version=6.10.0&hosts=&consentId=a6b045c0-2232-4a78-93be-dd3d9115dcce&interactionCount=1&landingPath=NotLandingPage&groups=C0003%3A1%2CC0001%3A1%2CC0002%3A1%2CC0005%3A1%2CC0004%3A1%2CSPD_BG%3A1%2CC0007%3A1&AwaitingReconsent=false; IR_1305=1608623152484%7C0%7C1608623140701%7C%7C; IR_PI=5d219c2b-3076-11eb-8648-42010a24661c%7C1608709552484; ajs_user_id=%22193863988%22; ajs_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; _uetsid=1a0a2a60437011eb97bf0727594fa669; _uetvid=5d29a3a0307611eb926f21f42ecb5121; _4c_=jVNNc9sgEP0rHs7B5lvItzanzvSW3jMIcKWxLDSArLge%2F%2FcusmOnmRyqi9jH7nu8ZTmjufUD2lJFtGKccSXq%2Bgnt%2FSmh7RnFzpXfEW1Rraz1lWzwThmNBWcV1tIyrLipmXK1klagJ%2FRWuKhgnBOhtdSXJ2THG8cZTbEHqjbnMW03m3me16mdcvYx5WD3axsOm76zfkg%2BbY6d8wEY%2FVDkx%2BhgnVLepy57AIy1YRpyAacm2diNuQvDr9NYNl8%2BQGmFV1yS1bdhmEwP%2BVPy8SWbPIFFZCeQPvgI%2BMIXT0VsD6EZwnA6hCn9gB4g6isnDWVY6GK%2FaSw20gnMKk0ZGN050SwcrujTek3pmgGQ%2F0DICYHlGIObbH7N1zPOvlklV5ScP4Lp17lzuV2KGXmgre9%2Bt7nARC%2FwGEsAq7kbXJgfZVroB3ov01XJbWKYwTbEz20EvytdARpKB352w%2FQGQfQ7H%2BOSU5p67fLn67ntwHx82iz%2Byj3JotYHa%2FpSDqMFBd6We4DwO3tGl9uMEF2RWnEhRQVdyjAXWglSvsuVahkZSf8n%2FeoOAz32w72Wk39rqSb6C6n%2Blv5QUkwpzqhUt2wq7tnH7v098MrVhmqJDfEwFVVVY2OIwcb7HasFaxgn6AOlJJxW9fsB4CwL4%2BXyFw%3D%3D; _actcc=20.8.326.112; _actmu=ee8b9134-5946-4f13-9e56-0844fe5f9346; _actms=b841aaf4-5bf9-469a-911f-6fd5ad4bae07';
            //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                
            //     $result = curl_exec($ch);
            //     if (curl_errno($ch)) {
            //         echo 'Error:' . curl_error($ch);
            //     }
            //     curl_close($ch);
                
            //     $rows = json_decode($result,true)['included'];
            //     foreach($rows as $row){
                    
            //         if($row['type'] == 'products' || Resource::where('sourceable_id' ,$row['id'])->get()->first()  ){
            //             continue;
            //         }
                    
            //         $resource = new Resource();
            //         $resource->sourceable_id   = $row['id'];
            //         $resource->resource_category_id =  '2';
            //         $resource->sourceable_type      = 'shutterstock';
            //         $resource->sourceable_format    = "none";
            //         $resource->title                = $row['attributes']['description'] ? $row['attributes']['description'] : "" ;
            //         $resource->description          = $row['attributes']['description'] ? $row['attributes']['description'] : "";
            //         $resource->keywords             = $row['attributes']['keywords'] ? $row['attributes']['keywords'] : [];
            //         $resource->image                = $row['attributes']['preview_image_url'] ? $row['attributes']['preview_image_url'] : "";
            //         $resource->preview_video_url    = $row['attributes']['preview_video_urls']['mp4'] ? $row['attributes']['preview_video_urls']['mp4'] : '' ; 
            //         // $resource->sourceable_account   = "ryder.treac@gmail.com"; // default is big shutter account of admin360
            //         $resource->save();              
                   
                    
            //     }
               
            // }
           
        
    }
    
    // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/

    public function scrapAndDownkloadVideos(){
        
        // $resources = Resource::where('id', '>' , 24026)->get();
        // foreach( $resources as $resource ){
        //     $keywords  = $resource->keywords;
        //     $keywords[] = "All";
        //     $resource->keywords = $keywords;
        //     $resource->save();
        // }
        
        // $resources = Resource::where('id','>','24026')->get();
        // foreach($resources as $resource){
        //     echo $resource->sourceable_id.','.( file_exists("/home/eworldclients/public_html/demo/library/storage/app/public/resources/images/original/shutterstock_".$resource->sourceable_id.'.'.$resource->sourceable_format ) );
        //     echo "<br/>";
        // }
                
        // $resources = Resource::where('resource_category_id','2')->where('sourceable_downloaded',null)->where('sourceable_download_link','!=', null)->get()->take(15);
        // foreach($resources as $resource){
        //         $link    = $resource->sourceable_download_link ;
        //         $contents= file_get_contents($link);
        //         file_put_contents('/home/eworldclients/public_html/demo/library/storage/app/public/resources/images/videos/shutterstock_'.$resource->sourceable_id.'.'.$resource->sourceable_format, $contents);
        //         $resource->sourceable_downloaded = '1';
        //         $resource->save();
        //  }        
         
        
        
       
        // $resources = Resource::where('resource_category_id','2')->where('sourceable_download_link', null)->get()->take(15);
        // foreach($resources as $resource){
            
        //     // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        //     $ch = curl_init();
            
        //     curl_setopt($ch, CURLOPT_URL, 'https://www.shutterstock.com/studioapi/videos/'.$resource->sourceable_id.'?field%5Bvideos%5D=sizes');
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            
        //     curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            
        //     $headers = array();
        //     $headers[] = 'Authority: www.shutterstock.com';
        //     $headers[] = 'X-End-User-Ua: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36';
        //     $headers[] = 'Dnt: 1';
        //     $headers[] = 'X-End-User-Ip: 124.29.206.134';
        //     $headers[] = 'Authorization: Bearer 1/eyJjbGllbnRfaWQiOiI0ZGVlMi04Zjc3NS1kZDRjNi00ZTU2MS02ZTY0NS0xYWEwZiIsInJlYWxtIjoiY3VzdG9tZXIiLCJzY29wZSI6InVzZXIudmlldyB1c2VyLmVtYWlsIHVzZXIuYWRkcmVzcyB1c2VyLmVkaXQgb3JnYW5pemF0aW9uLnZpZXcgb3JnYW5pemF0aW9uLmFkZHJlc3MgY29sbGVjdGlvbnMudmlldyBjb2xsZWN0aW9ucy5lZGl0IGxpY2Vuc2VzLnZpZXcgbGljZW5zZXMuY3JlYXRlIG1lZGlhLnVwbG9hZCBtZWRpYS5zdWJtaXQgbWVkaWEuZWRpdCBwdXJjaGFzZXMudmlldyBwdXJjaGFzZXMuY3JlYXRlIiwidXR2IjoiWFVjMiIsInVzZXJuYW1lIjoiYWRtaW4yNzU1ODczIiwidXNlcl9pZCI6MTkzODYzOTg4LCJvcmdhbml6YXRpb25faWQiOm51bGwsIm9yZ2FuaXphdGlvbl91c2VyX2lkIjpudWxsLCJwYXJlbnRfb3JnYW5pemF0aW9uX2lkcyI6W10sImN1c3RvbWVyX2lkIjoxOTM4NjM5ODgsImV4cCI6MTYwODYyNjc0NH0.lzt6-BtxI7xW5jOvvUcQ3_o87snJdg5yjl_9QFVc9WSPtHrK4u7QpbeqJK5U0K6S2JFJnqXifikKa8Cg17Oupg';
        //     $headers[] = 'X-Shutterstock-Features: tokencide';
        //     $headers[] = 'X-Shutterstock-User-Token: eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzc3RrX2RhdGEiOnsiaWQiOjE5Mzg2Mzk4OCwibG9jYWxlcyI6WyJlbiIsInpoLUhhbnQiXSwiY3JlYXRlX3RpbWUiOiIyMDE4LTAyLTE5VDIxOjEyOjE3LjAwMFoifSwic3N0a19wcml2X2RhdGEiOiJhNDZjYTNiYzdjYzZmN2Y0Njc3ZTkzM2U3YTAyMWJkZC45NTQzYzNlN2QyNjZlNWVlMjEwZGFjMzVlMGUxNzc4YmZlMmI2YzZhMDQ2ZTc2M2M1NWJhNjc5YjExYmUyOTk0OGIxM2RlZGI2ZmQ3OTI2YWU2YjQ0ZmRhZjFlNGQ5Yjk4NTA5ODBlZDE0ZDk2ZjVjNjdkMDRjZTYxMTYzZmZiZGFlMThiMWNiM2JkOWYwNmRmNzRhY2I1M2IxMzA2NjkwNzNlYzYzY2UwZjdlNmE2NjAyNTBkMTkzN2RkYmY2MGUiLCJpYXQiOjE2MDg2MjMxNDQsImlzcyI6ImFjY291bnRzIiwic3ViIjoidXNlci4xOTM4NjM5ODgifQ.nLEWUpT76aOnbWyHFDK-ytZ2iGtUbgFiraSNQpf0msl2caosoKHQPgbp8PB5rP_zJ7ZO-QKnpboBKpSDkTT_0WjYTOPS8iUKyKvypXMp6ZN3d8sYQ_fmmDV4lghQrTTcSC_VWuQWBcJOwJoeQA0nVoTFwl76oTsmQ01NVmnB623pQe6lStFMDK_7DdJcuPu-UQwaKPkZwUBEjYUL1B8h0VVVWzlB4SWGNqwt7Vntovp_2qhbVvRv9Sd-DVrrHkYiUG_Wr4lSQGbrmuDctg_yFDUyQIPUJ1f9cbnDLxEbT_bZoTyBos_VFzctYJLZniIKUUu5MTJAnVkVidugbOs574NN4csTqQP2b-2Qy9A_ce-TFkCPfkVDqIvktTl6QkUOCqzbGYWnexo5E5kU3fHxwWasAwcHYKub4mTPKhFRQ5fZqofMjKEfq6CDBQe5L2SYLXPhkA9U011ZtHIIBSP6jMV97_1ojOZDtOCNl0hzxRAAnbyKFkiGwZu6UvJO_UqDV4iLWAEiRMN07FkH3duPODp9aUFagNesfLX8x3eMMcj0mP2hkVuooRR866jMm7HrEJa6hjiFJQiw83q5D4q0O8O82Zim4jQvKxhqdP7NjvtLVMxm7gBL8cWbEiM7TQ5Xd7YS-P7pBe53qqezSwF5MKxKURG8NV6a-N8Zxqr_lYw';
        //     $headers[] = 'X-Shutterstock-Search-Id: null';
        //     $headers[] = 'X-End-User-Country: PK';
        //     $headers[] = 'X-Request-Id: ae35490b-fba1-4410-b275-b6b2d019f063';
        //     $headers[] = 'X-End-User-Visit-Id: 69664821628';
        //     $headers[] = 'X-Shutterstock-Site-Section: sstk/licenses/video';
        //     $headers[] = 'X-End-App-Version: 0.1134.6';
        //     $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36';
        //     $headers[] = 'Content-Type: application/json';
        //     $headers[] = 'X-End-App-Name: studio-web';
        //     $headers[] = 'Accept: application/json';
        //     $headers[] = 'X-End-User-Id: 193863988';
        //     $headers[] = 'X-End-User-Visitor-Id: 63848983170';
        //     $headers[] = 'Sec-Fetch-Site: same-origin';
        //     $headers[] = 'Sec-Fetch-Mode: cors';
        //     $headers[] = 'Sec-Fetch-Dest: empty';
        //     $headers[] = 'Referer: https://www.shutterstock.com/licenses/video';
        //     $headers[] = 'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8';
        //     $headers[] = 'Cookie: visitor_id=63848983170; did=5745bd6b-3bc0-475b-a042-992e92bdc924; connect.sid=s%3ANEsM_pPmq6BruyAh-3KgPk6O9-vWnJ0J.mSCdhXOKBvYXCV3eQ%2FLbtbxnTe6gJw1zHbgn4%2FRy7nI; __ssid=2df00e6272821102261db2c18419ea5; optimizelyEndUserId=oeu1606457031873r0.113129512866039; _gcl_au=1.1.1393381827.1606457034; C3UID-924=4120151631606457035; C3UID=4120151631606457035; sstk_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; IR_gbd=shutterstock.com; _ts_yjad=1606457049145; __qca=P0-661513937-1606457048890; _ga=GA1.2.2075455506.1606457070; ajs_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; _cs_ex=1596812801; _cs_c=1; _ym_uid=1606709788437414803; _ym_d=1606709788; usi_analytics=a_3kb80s_1606709789_5698; _biz_uid=2a77cc1f179742859ad0873b14592ad7; _biz_flagsA=%7B%22Version%22%3A1%2C%22ViewThrough%22%3A%221%22%2C%22XDomain%22%3A%221%22%7D; ei_client_id=5fd84cf4806cad0011cfa720; driftt_aid=2f6bfc96-a87e-46a4-a17d-5ae9452a24da; DFTT_END_USER_PREV_BOOTSTRAPPED=true; _biz_nA=2; _biz_pendingA=%5B%5D; search=/search/gandhi?; footage_search_tracking_id=540c7f42-a05e-4bd9-b1b8-435ed63f06eb; locale=en-GB; usi_existing_customer=1; _gid=GA1.2.1166992505.1608543432; _ym_isad=1; _actts=1606457047.1608612225.1608617815; _actvc=18; _cs_mk=0.555850021624168_1608623121188; lihptb=0; sstk.sid=s%3AWKrS3WGSSBSsUeuVT4kDWVNFFuM52MFG.Pzskgb%2BZHSJn5DVkdtBWbS2oK5yZqQvnqRMVfoFK8pE; visit_id=69664821628; accts_customer=admin2755873; accts_customer_sso1=193863988-undefined; OptanonConsent=isIABGlobal=false&datestamp=Tue+Dec+22+2020+12%3A45%3A50+GMT%2B0500+(Pakistan+Standard+Time)&version=6.10.0&hosts=&consentId=a6b045c0-2232-4a78-93be-dd3d9115dcce&interactionCount=1&landingPath=NotLandingPage&groups=C0003%3A1%2CC0001%3A1%2CC0002%3A1%2CC0005%3A1%2CC0004%3A1%2CSPD_BG%3A1%2CC0007%3A1&AwaitingReconsent=false; IR_1305=1608623152484%7C0%7C1608623140701%7C%7C; IR_PI=5d219c2b-3076-11eb-8648-42010a24661c%7C1608709552484; ajs_user_id=%22193863988%22; ajs_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; _uetsid=1a0a2a60437011eb97bf0727594fa669; _uetvid=5d29a3a0307611eb926f21f42ecb5121; _actcc=20.8.326.112; _4c_=jVNNc9sgEP0rHs7B5lvItzanzvSW3jMIcKWxLDSArLge%2F%2FcusmOnmRyqi9jH7nu8ZTmjufUD2lJFtGKCaKo5e0J7f0poe0axc%2BV3RFtUK2t9JRu8U0ZjwVmFtbQMK25qplytpBXoCb0VLioY50RoLfXlCdnxxnFGU%2ByBqs15TNvNZp7ndWqnnH1MOdj92obDpu%2BsH5JPm2PnfABGPxT5MTpYp5T3qcseAGNtmIZcwKlJNnZj7sLw6zSWzZcPUFrhFZdk9W0YJtND%2FpR8fMkmT2AR2QmkDz4CvvDFUxHbQ2iGMJwOYUo%2FoAeI%2BspJQxkWuthvGouNdAKzSlMGRndONAuHK%2Fq0XlO6ZgDkPxByQmA5xuAmm1%2Fz9Yyzb1bJFSXnj2D6de5cbpdiRh5o67vfbS4w0Qs8xhLAau4GF%2BZHmRb6gd7LdFVymxhmsA3xcxvB70pXgIbSgZ%2FdML1BEP3Ox7jklKZeu%2Fz5em47MB%2BfNou%2Fck%2ByqPXBmr6Uw2hBgbflHiD8zp7R5TYjRFekVlxIUUGXMsyFVoKU73KlWkZG0v9Jv7rDQI%2F9cK%2Fl5N9aqon%2BQqq%2FpT%2BUFFOKMyrVLZuKe%2Faxe38PvHK1oVpiQzxMRVXV2BhisPF%2Bx2rBGsYJ%2BkApCadV%2FX4AOMvCeLn8BQ%3D%3D; _gat_UA-32034-16=1; _actmu=ee8b9134-5946-4f13-9e56-0844fe5f9346; _actms=b841aaf4-5bf9-469a-911f-6fd5ad4bae07';
        //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
        //     $result = curl_exec($ch);
        //     if (curl_errno($ch)) {
        //         echo 'Error:' . curl_error($ch);
        //     }
        //     curl_close($ch);
            
        //     $sizes =  json_decode($result,true)['data']['attributes']['sizes'];
        //     $format = array_key_exists('hd_mpeg', $sizes) ?  $sizes['hd_mpeg']['format'] : ( array_key_exists('hd_original', $sizes)  ? $sizes['hd_original']['format'] : $sizes['sd_original']['format'] );
           
            

            
            
            
        //   // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        //     $ch = curl_init();
            
        //     curl_setopt($ch, CURLOPT_URL, 'https://www.shutterstock.com/studioapi/downloads');
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //     curl_setopt($ch, CURLOPT_POST, 1);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"data\":[{\"media_type\":\"video\",\"media_id\":\"".$resource->sourceable_id."\",\"media_size\":\"hd\",\"media_format\":\"".$format."\"}]}");
        //     curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            
        //     $headers = array();
        //     $headers[] = 'Authority: www.shutterstock.com';
        //     $headers[] = 'X-End-User-Ua: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36';
        //     $headers[] = 'Dnt: 1';
        //     $headers[] = 'X-End-User-Ip: 124.29.206.134';
        //     $headers[] = 'Authorization: Bearer 1/eyJjbGllbnRfaWQiOiI0ZGVlMi04Zjc3NS1kZDRjNi00ZTU2MS02ZTY0NS0xYWEwZiIsInJlYWxtIjoiY3VzdG9tZXIiLCJzY29wZSI6InVzZXIudmlldyB1c2VyLmVtYWlsIHVzZXIuYWRkcmVzcyB1c2VyLmVkaXQgb3JnYW5pemF0aW9uLnZpZXcgb3JnYW5pemF0aW9uLmFkZHJlc3MgY29sbGVjdGlvbnMudmlldyBjb2xsZWN0aW9ucy5lZGl0IGxpY2Vuc2VzLnZpZXcgbGljZW5zZXMuY3JlYXRlIG1lZGlhLnVwbG9hZCBtZWRpYS5zdWJtaXQgbWVkaWEuZWRpdCBwdXJjaGFzZXMudmlldyBwdXJjaGFzZXMuY3JlYXRlIiwidXR2IjoiWFVjMiIsInVzZXJuYW1lIjoiYWRtaW4yNzU1ODczIiwidXNlcl9pZCI6MTkzODYzOTg4LCJvcmdhbml6YXRpb25faWQiOm51bGwsIm9yZ2FuaXphdGlvbl91c2VyX2lkIjpudWxsLCJwYXJlbnRfb3JnYW5pemF0aW9uX2lkcyI6W10sImN1c3RvbWVyX2lkIjoxOTM4NjM5ODgsImV4cCI6MTYwODYyNjc0NH0.lzt6-BtxI7xW5jOvvUcQ3_o87snJdg5yjl_9QFVc9WSPtHrK4u7QpbeqJK5U0K6S2JFJnqXifikKa8Cg17Oupg';
        //     $headers[] = 'X-Shutterstock-Features: tokencide';
        //     $headers[] = 'X-Shutterstock-User-Token: eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzc3RrX2RhdGEiOnsiaWQiOjE5Mzg2Mzk4OCwibG9jYWxlcyI6WyJlbiIsInpoLUhhbnQiXSwiY3JlYXRlX3RpbWUiOiIyMDE4LTAyLTE5VDIxOjEyOjE3LjAwMFoifSwic3N0a19wcml2X2RhdGEiOiJhNDZjYTNiYzdjYzZmN2Y0Njc3ZTkzM2U3YTAyMWJkZC45NTQzYzNlN2QyNjZlNWVlMjEwZGFjMzVlMGUxNzc4YmZlMmI2YzZhMDQ2ZTc2M2M1NWJhNjc5YjExYmUyOTk0OGIxM2RlZGI2ZmQ3OTI2YWU2YjQ0ZmRhZjFlNGQ5Yjk4NTA5ODBlZDE0ZDk2ZjVjNjdkMDRjZTYxMTYzZmZiZGFlMThiMWNiM2JkOWYwNmRmNzRhY2I1M2IxMzA2NjkwNzNlYzYzY2UwZjdlNmE2NjAyNTBkMTkzN2RkYmY2MGUiLCJpYXQiOjE2MDg2MjMxNDQsImlzcyI6ImFjY291bnRzIiwic3ViIjoidXNlci4xOTM4NjM5ODgifQ.nLEWUpT76aOnbWyHFDK-ytZ2iGtUbgFiraSNQpf0msl2caosoKHQPgbp8PB5rP_zJ7ZO-QKnpboBKpSDkTT_0WjYTOPS8iUKyKvypXMp6ZN3d8sYQ_fmmDV4lghQrTTcSC_VWuQWBcJOwJoeQA0nVoTFwl76oTsmQ01NVmnB623pQe6lStFMDK_7DdJcuPu-UQwaKPkZwUBEjYUL1B8h0VVVWzlB4SWGNqwt7Vntovp_2qhbVvRv9Sd-DVrrHkYiUG_Wr4lSQGbrmuDctg_yFDUyQIPUJ1f9cbnDLxEbT_bZoTyBos_VFzctYJLZniIKUUu5MTJAnVkVidugbOs574NN4csTqQP2b-2Qy9A_ce-TFkCPfkVDqIvktTl6QkUOCqzbGYWnexo5E5kU3fHxwWasAwcHYKub4mTPKhFRQ5fZqofMjKEfq6CDBQe5L2SYLXPhkA9U011ZtHIIBSP6jMV97_1ojOZDtOCNl0hzxRAAnbyKFkiGwZu6UvJO_UqDV4iLWAEiRMN07FkH3duPODp9aUFagNesfLX8x3eMMcj0mP2hkVuooRR866jMm7HrEJa6hjiFJQiw83q5D4q0O8O82Zim4jQvKxhqdP7NjvtLVMxm7gBL8cWbEiM7TQ5Xd7YS-P7pBe53qqezSwF5MKxKURG8NV6a-N8Zxqr_lYw';
        //     $headers[] = 'X-Shutterstock-Search-Id: null';
        //     $headers[] = 'X-End-User-Country: PK';
        //     $headers[] = 'X-Request-Id: 170e2948-a2e4-4db1-a3e8-0811c30a6326';
        //     $headers[] = 'X-End-User-Visit-Id: 69664821628';
        //     $headers[] = 'X-Shutterstock-Site-Section: sstk/licenses/video';
        //     $headers[] = 'X-End-App-Version: 0.1134.6';
        //     $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36';
        //     $headers[] = 'Content-Type: application/json';
        //     $headers[] = 'X-End-App-Name: studio-web';
        //     $headers[] = 'Accept: application/json';
        //     $headers[] = 'X-End-User-Id: 193863988';
        //     $headers[] = 'X-End-User-Visitor-Id: 63848983170';
        //     $headers[] = 'Origin: https://www.shutterstock.com';
        //     $headers[] = 'Sec-Fetch-Site: same-origin';
        //     $headers[] = 'Sec-Fetch-Mode: cors';
        //     $headers[] = 'Sec-Fetch-Dest: empty';
        //     $headers[] = 'Referer: https://www.shutterstock.com/licenses/video';
        //     $headers[] = 'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8';
        //     $headers[] = 'Cookie: visitor_id=63848983170; did=5745bd6b-3bc0-475b-a042-992e92bdc924; connect.sid=s%3ANEsM_pPmq6BruyAh-3KgPk6O9-vWnJ0J.mSCdhXOKBvYXCV3eQ%2FLbtbxnTe6gJw1zHbgn4%2FRy7nI; __ssid=2df00e6272821102261db2c18419ea5; optimizelyEndUserId=oeu1606457031873r0.113129512866039; _gcl_au=1.1.1393381827.1606457034; C3UID-924=4120151631606457035; C3UID=4120151631606457035; sstk_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; IR_gbd=shutterstock.com; _ts_yjad=1606457049145; __qca=P0-661513937-1606457048890; _ga=GA1.2.2075455506.1606457070; ajs_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; _cs_ex=1596812801; _cs_c=1; _ym_uid=1606709788437414803; _ym_d=1606709788; usi_analytics=a_3kb80s_1606709789_5698; _biz_uid=2a77cc1f179742859ad0873b14592ad7; _biz_flagsA=%7B%22Version%22%3A1%2C%22ViewThrough%22%3A%221%22%2C%22XDomain%22%3A%221%22%7D; ei_client_id=5fd84cf4806cad0011cfa720; driftt_aid=2f6bfc96-a87e-46a4-a17d-5ae9452a24da; DFTT_END_USER_PREV_BOOTSTRAPPED=true; _biz_nA=2; _biz_pendingA=%5B%5D; search=/search/gandhi?; footage_search_tracking_id=540c7f42-a05e-4bd9-b1b8-435ed63f06eb; locale=en-GB; usi_existing_customer=1; _gid=GA1.2.1166992505.1608543432; _ym_isad=1; _actts=1606457047.1608612225.1608617815; _actvc=18; _cs_mk=0.555850021624168_1608623121188; lihptb=0; sstk.sid=s%3AWKrS3WGSSBSsUeuVT4kDWVNFFuM52MFG.Pzskgb%2BZHSJn5DVkdtBWbS2oK5yZqQvnqRMVfoFK8pE; visit_id=69664821628; accts_customer=admin2755873; accts_customer_sso1=193863988-undefined; OptanonConsent=isIABGlobal=false&datestamp=Tue+Dec+22+2020+12%3A45%3A50+GMT%2B0500+(Pakistan+Standard+Time)&version=6.10.0&hosts=&consentId=a6b045c0-2232-4a78-93be-dd3d9115dcce&interactionCount=1&landingPath=NotLandingPage&groups=C0003%3A1%2CC0001%3A1%2CC0002%3A1%2CC0005%3A1%2CC0004%3A1%2CSPD_BG%3A1%2CC0007%3A1&AwaitingReconsent=false; IR_1305=1608623152484%7C0%7C1608623140701%7C%7C; IR_PI=5d219c2b-3076-11eb-8648-42010a24661c%7C1608709552484; ajs_user_id=%22193863988%22; ajs_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; _uetsid=1a0a2a60437011eb97bf0727594fa669; _uetvid=5d29a3a0307611eb926f21f42ecb5121; _actcc=20.8.326.112; _4c_=jVNNc9sgEP0rHs7B5lvItzanzvSW3jMIcKWxLDSArLge%2F%2FcusmOnmRyqi9jH7nu8ZTmjufUD2lJFtGKCaKo5e0J7f0poe0axc%2BV3RFtUK2t9JRu8U0ZjwVmFtbQMK25qplytpBXoCb0VLioY50RoLfXlCdnxxnFGU%2ByBqs15TNvNZp7ndWqnnH1MOdj92obDpu%2BsH5JPm2PnfABGPxT5MTpYp5T3qcseAGNtmIZcwKlJNnZj7sLw6zSWzZcPUFrhFZdk9W0YJtND%2FpR8fMkmT2AR2QmkDz4CvvDFUxHbQ2iGMJwOYUo%2FoAeI%2BspJQxkWuthvGouNdAKzSlMGRndONAuHK%2Fq0XlO6ZgDkPxByQmA5xuAmm1%2Fz9Yyzb1bJFSXnj2D6de5cbpdiRh5o67vfbS4w0Qs8xhLAau4GF%2BZHmRb6gd7LdFVymxhmsA3xcxvB70pXgIbSgZ%2FdML1BEP3Ox7jklKZeu%2Fz5em47MB%2BfNou%2Fck%2ByqPXBmr6Uw2hBgbflHiD8zp7R5TYjRFekVlxIUUGXMsyFVoKU73KlWkZG0v9Jv7rDQI%2F9cK%2Fl5N9aqon%2BQqq%2FpT%2BUFFOKMyrVLZuKe%2Faxe38PvHK1oVpiQzxMRVXV2BhisPF%2Bx2rBGsYJ%2BkApCadV%2FX4AOMvCeLn8BQ%3D%3D; _gat_UA-32034-16=1; _actmu=ee8b9134-5946-4f13-9e56-0844fe5f9346; _actms=b841aaf4-5bf9-469a-911f-6fd5ad4bae07';
        //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
        //     $result = curl_exec($ch);
        //     if (curl_errno($ch)) {
        //         echo 'Error:' . curl_error($ch);
        //     }
        //     curl_close($ch);

            
        //     if($result){
                
        //         $download_url = json_decode($result,true)['data'][0]['attributes']['url'];
        //         $resource->sourceable_download_link = $download_url;
        //         $resource->sourceable_format = $format;
        //         $resource->save();
        //     }
        
        // }
    }    
    
    
    
    public function getSrcAndDownloadImages(){
         
        // $resources = Resource::where('sourceable_downloaded',null)->where('sourceable_download_link','!=', null)->get()->take(225);
        // foreach($resources as $resource){
        //         $link    = $resource->sourceable_download_link ;
        //         $contents= file_get_contents($link);
        //         file_put_contents('/home/eworldclients/public_html/demo/library/storage/app/public/resources/images/original/shutterstock_'.$resource->sourceable_id.'.'.$resource->sourceable_format , $contents);
        //         $resource->sourceable_downloaded = '1';
        //         $resource->save();
        // }        
      
       
        // $resources = Resource::where('resource_category_id','5')->where('sourceable_download_link', null)->get()->take(1000);
        // foreach($resources as $resource){
          
        //     // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        //   // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        // $ch = curl_init();
        
        // curl_setopt($ch, CURLOPT_URL, 'https://www.shutterstock.com/studioapi/licensees/current/redownload');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"content\":[{\"content_format\":\"eps\",\"content_id\":\"".$resource->sourceable_id."\",\"content_size\":\"vector\",\"content_type\":\"photo\",\"license_name\":\"standard\",\"show_modal\":true}],\"country_code\":\"PK\",\"required_cookies\":\"\"}");
        // curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        
        // $headers = array();
        // $headers[] = 'Authority: www.shutterstock.com';
        // $headers[] = 'X-End-User-Ua: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36';
        // $headers[] = 'Dnt: 1';
        // $headers[] = 'X-End-User-Ip: 124.29.206.134';
        // $headers[] = 'Authorization: Bearer 1/eyJjbGllbnRfaWQiOiI0ZGVlMi04Zjc3NS1kZDRjNi00ZTU2MS02ZTY0NS0xYWEwZiIsInJlYWxtIjoiY3VzdG9tZXIiLCJzY29wZSI6InVzZXIudmlldyB1c2VyLmVtYWlsIHVzZXIuYWRkcmVzcyB1c2VyLmVkaXQgb3JnYW5pemF0aW9uLnZpZXcgb3JnYW5pemF0aW9uLmFkZHJlc3MgY29sbGVjdGlvbnMudmlldyBjb2xsZWN0aW9ucy5lZGl0IGxpY2Vuc2VzLnZpZXcgbGljZW5zZXMuY3JlYXRlIG1lZGlhLnVwbG9hZCBtZWRpYS5zdWJtaXQgbWVkaWEuZWRpdCBwdXJjaGFzZXMudmlldyBwdXJjaGFzZXMuY3JlYXRlIiwidXR2IjoianZCeCIsInVzZXJuYW1lIjoicnlkZXJ0cmVhYzcxIiwidXNlcl9pZCI6MjU1MDA1NzcxLCJvcmdhbml6YXRpb25faWQiOm51bGwsIm9yZ2FuaXphdGlvbl91c2VyX2lkIjpudWxsLCJwYXJlbnRfb3JnYW5pemF0aW9uX2lkcyI6W10sImN1c3RvbWVyX2lkIjoyNTUwMDU3NzEsImV4cCI6MTYwODYxNTk1Nn0.R_xRcsxwiGWPV1FuMVLEpPhQlvWk4juwjS5Sfcgo_LFgczeMxHXi_2lBSLbyHFfZvgBM_DldsnKjueG5ImF-Kw';
        // $headers[] = 'X-Shutterstock-Features: tokencide';
        // $headers[] = 'X-Shutterstock-User-Token: eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzc3RrX2RhdGEiOnsiaWQiOjI1NTAwNTc3MSwibG9jYWxlcyI6WyJlbiJdLCJjcmVhdGVfdGltZSI6IjIwMjAtMDEtMDJUMTc6MDI6MDQuMDAwWiJ9LCJzc3RrX3ByaXZfZGF0YSI6ImE4NWU2Mjk5ODZmYjg1YmYxNDI1MjllOGFlM2FhZGQwLjc5MmRhYjE3MDVkY2NlN2IzZjExOTEwNzdmN2JkZTAwZDBjZTk4YmJjMjg2YWE0NzRjOTc2ZjVjMGFmODY3NDlmOGJmYjA5MDQ1N2Y5ZTRlOGM0M2RlZjkzOGY1YmJmODI1NTkzY2E1NzAyZmQ2NjEyN2RkYWU0MWU3ZTBiMDU1MDcxOTE0ZTE4ZTFhOTJlNTUwMGM1MjQ0YjVjMDBjZDMiLCJpYXQiOjE2MDg2MTIzNTYsImlzcyI6ImFjY291bnRzIiwic3ViIjoidXNlci4yNTUwMDU3NzEifQ.rEwUjxEKGI5CSq53ryJUTccU0_MOk7p5Klpe0qGAOB8QOpMiQsp9LTflRnu1-FzXmTj85SpeFvLlV7fCeaINAi3B3TKfgHqcdhgSiMqDCM0ftZZNHf2lnP-1hGVrfVnZzWTp9B4uvzqwKlbdJZJGfXlTZoSvOQOB1tQXOrZFgBEXN6aVaj3pxcbUsccDi-9EYgSdzfjTC53sFriohBZ67RO39-Dd0O-N-tnblrzRqthrMSfdQjlsDkTDiOEejL-AQo9osyAWtzMtWALX9WliwU-IIy6Dt1xs0TV2JDrxlkVGSfX8t8DQgcYh0KEO5ZWXRVYuetDbvXMso25C0HvRhPqW6jQ-HyORKTLsIMShNM2LhSpBZMz4ZbGQW1EwijaznG2lQBbrM5Jd1twIouqga0YODZ1wxImyvn2cLyyIsBEn5ehQAR13-W3rkDFirPsTVTHx-USa0oTW50EFgYjus2wyP4DHUfS2BX6HgJcOrcNcbS_a7fVdY-hb7wV4o9R95LG5qzmtIVghyDQxqxKfojxJ43FjoW5400HjMdUJyaUJ7Po96v5sJvWEiT8bvOmFtKmG42v7tvr6uw-0jofWoyVO6QP4pXLo8KqAG5Ei3dNYxotDNpy0Tqv1hzEXJpZh_CPWL8VUvNYQ-pDMuSlXAzm3riVadO6dqfIV1j6A1pg';
        // $headers[] = 'X-Shutterstock-Search-Id: null';
        // $headers[] = 'X-End-User-Country: PK';
        // $headers[] = 'X-Request-Id: e61b60dc-8002-4165-9cff-f9363d1e4227';
        // $headers[] = 'X-End-User-Visit-Id: 69663977899';
        // $headers[] = 'X-Shutterstock-Site-Section: sstk/licenses/image';
        // $headers[] = 'X-End-App-Version: 0.1134.6';
        // $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36';
        // $headers[] = 'Content-Type: application/json';
        // $headers[] = 'X-End-App-Name: studio-web';
        // $headers[] = 'Accept: application/json';
        // $headers[] = 'X-End-User-Id: 255005771';
        // $headers[] = 'X-End-User-Visitor-Id: 63848983170';
        // $headers[] = 'Origin: https://www.shutterstock.com';
        // $headers[] = 'Sec-Fetch-Site: same-origin';
        // $headers[] = 'Sec-Fetch-Mode: cors';
        // $headers[] = 'Sec-Fetch-Dest: empty';
        // $headers[] = 'Referer: https://www.shutterstock.com/licenses/image';
        // $headers[] = 'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8';
        // $headers[] = 'Cookie: visitor_id=63848983170; did=5745bd6b-3bc0-475b-a042-992e92bdc924; connect.sid=s%3ANEsM_pPmq6BruyAh-3KgPk6O9-vWnJ0J.mSCdhXOKBvYXCV3eQ%2FLbtbxnTe6gJw1zHbgn4%2FRy7nI; __ssid=2df00e6272821102261db2c18419ea5; optimizelyEndUserId=oeu1606457031873r0.113129512866039; _gcl_au=1.1.1393381827.1606457034; C3UID-924=4120151631606457035; C3UID=4120151631606457035; sstk_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; IR_gbd=shutterstock.com; _ts_yjad=1606457049145; __qca=P0-661513937-1606457048890; _ga=GA1.2.2075455506.1606457070; ajs_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; _cs_ex=1596812801; _cs_c=1; _ym_uid=1606709788437414803; _ym_d=1606709788; usi_analytics=a_3kb80s_1606709789_5698; _biz_uid=2a77cc1f179742859ad0873b14592ad7; _biz_flagsA=%7B%22Version%22%3A1%2C%22ViewThrough%22%3A%221%22%2C%22XDomain%22%3A%221%22%7D; ei_client_id=5fd84cf4806cad0011cfa720; driftt_aid=2f6bfc96-a87e-46a4-a17d-5ae9452a24da; DFTT_END_USER_PREV_BOOTSTRAPPED=true; _biz_nA=2; _biz_pendingA=%5B%5D; search=/search/gandhi?; footage_search_tracking_id=540c7f42-a05e-4bd9-b1b8-435ed63f06eb; locale=en-GB; usi_existing_customer=1; _gid=GA1.2.1166992505.1608543432; _ym_isad=1; _actts=1606457047.1608549674.1608612225; sstk.sid=s%3ARtUJeOA9QQJtIH0zDdnggwpkogPi_Rp8.lW1TOtijoFbl2f%2Fo8LQW%2BQhdwKVnwVTgczIlsaSLlWs; _actvc=17; AMP_TOKEN=%24NOT_FOUND; accts_customer=rydertreac71; accts_customer_sso1=255005771-undefined; ajs_user_id=%22255005771%22; ajs_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; IR_1305=1608612422701%7C0%7C1608612231831%7C%7C; IR_PI=5d219c2b-3076-11eb-8648-42010a24661c%7C1608698822701; _uetsid=1a0a2a60437011eb97bf0727594fa669; _uetvid=5d29a3a0307611eb926f21f42ecb5121; OptanonConsent=isIABGlobal=false&datestamp=Tue+Dec+22+2020+09%3A56%3A10+GMT%2B0500+(Pakistan+Standard+Time)&version=6.10.0&hosts=&consentId=a6b045c0-2232-4a78-93be-dd3d9115dcce&interactionCount=1&landingPath=NotLandingPage&groups=C0003%3A1%2CC0001%3A1%2CC0002%3A1%2CC0005%3A1%2CC0004%3A1%2CSPD_BG%3A1%2CC0007%3A1&AwaitingReconsent=false; _actcc=48.18.306.104; anonymousRendering=supported; _4c_=jVLBbuMgEP2ViHNJAAPGOW5PK%2B2euvcKA6lRHGMBjpuN8u87JG5SVXuoL2Ye894wM%2B%2BM5s4NaEslUZJywRjj9Ant3Smh7RlFb8vviLaokca4WrR4J7XCvGI1VsIwLCvdMGkbKQxHT%2Bi9aFHOqopwpYS6PCEzLhpnNMUepLqcx7TdbOZ5XqduytnFlIPZr004bHpv3JBc2viDfnOg6IZSfowWzinlffLZAXD01oUCTW0y0Y%2FZh%2BHPaSxXL5%2BgtMKrWpDV7zDkrj8BYUouvmSdJ%2BgQmQkqH1wE3IRpyPFUau0h1EMYTocwpZ8wAkRdbYWmDHNVum9bg7WwHLNaUQZ97ixvrxq2PIA2a0rXDID8F8KKEDiOMdjJ5Nd8e%2BTs2lWypZJ1R%2Bj5dfY2d1cyIw%2B0c%2F6tywUm6gqPsQRwmv1gw%2FygKa4e6J3W8LKVNoYZ2ob4uYvQ70rVgIYygV9%2BmN4hiG7nYrzmlKnehvx1O8sN2OPLZemvrKmScOqD0X2hg7OA4ExZBIQ%2F2DO6LBYBuzVNJQQYCaaUwRZKclK%2By03q6phKfif91h0GeeyGO5exz1xSq5r8h3v0HwZvW8UZ22ncCPA2r43FDSESN1o3ThvZMmnQQ5LyGp7DqkWSqvvj%2B0WRPpKZlBWjQn4k86X%2B5fIP; _gat_UA-32034-16=1; _actmu=ee8b9134-5946-4f13-9e56-0844fe5f9346; _actms=41bad9f3-c23a-4e04-8669-4b1979935dda';
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        // $result = curl_exec($ch);
        // if (curl_errno($ch)) {
        //     echo 'Error:' . curl_error($ch);
        // }
        // curl_close($ch);
            
        //     if($result){
        //         $download_url = json_decode($result,true)['meta']['licensed_content'][0]['download_url'];
        //         $resource->sourceable_download_link = $download_url;
        //         $resource->save();
        //     }
               
        // } 
        
    }
    
    public function scrapDownload() {
       
        
        $resources = Resource::where('id' , '>' , '29696')->where('resource_category_id',2)->get();
        foreach($resources as $resource){
            $crawler = Goutte::request('GET', $resource->sourceable_link);
            
            $tags = "";
            $crawler->filter('video')->each(function ($node) use(&$tags) {
                $tags .= $node->extract(['src'])[0];
                
            });
           
            $resource->preview_video_url = $tags;
            
        
            $resource->save();
        
        }
            
         
          
        
       
        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        //         $ch = curl_init();
        
        //         curl_setopt($ch, CURLOPT_URL, 'https://www.istockphoto.com/account/download/individual/credits.json?account_type=individual&download_state_filter=Purchased,Downloaded&page=1&page_size=178&product_type_filter=credits&search_by=date&view_page_size=178');
        //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                
        //         curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                
        //         $headers = array();
        //         $headers[] = 'Connection: keep-alive';
        //         $headers[] = 'Accept: application/json, text/plain, */*';
        //         $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36';
        //         $headers[] = 'Dnt: 1';
        //         $headers[] = 'Sec-Fetch-Site: same-origin';
        //         $headers[] = 'Sec-Fetch-Mode: cors';
        //         $headers[] = 'Sec-Fetch-Dest: empty';
        //         $headers[] = 'Referer: https://www.istockphoto.com/account/download/individual/credits?';
        //         $headers[] = 'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8';
        //         $headers[] = 'Cookie: vis=vid=f92169b5-a984-4fdc-8656-91812417eea7; _gcl_au=1.1.1804737810.1608120820; IR_gbd=istockphoto.com; _pin_unauth=dWlkPU1qTmtPVGRtWWpndE9HRmxNaTAwTVdZeUxXSmlNbU10TURNM1pUSXhaRGhoTURjMw; s_cc=true; s_fid=65E7FB7666F185C8-332C3BBC1F7D7916; s_sq=%5B%5BB%5D%5D; hpb=purchased=true; cdn.istockphoto.107800.ka.ck=411dccbf076a5e8ec03290a9bc6854cac6b6092641b9a552e0882e73002d302a355e78b5819ae6e99b0622d68a2103f0d58d40002ea283ddb296dbc61a4e99fb896d0163e78a946c58d067f4696f6ea9bdbffba069ab8a2dd10eefc2140be2476660df8817e0fd7cecbae552fab86d0fe63e193260a9c78a621eba88d5bba501f2065d1c3ab5b7248528c2c9e19d9dbdfb333fae57b4dbaa45bab1; _gid=GA1.2.328625558.1608631332; deftrack=sign_in_success=true&sign_out_success=true; dn=Retrocube; trk-event=event=event12; sign-in-event-sent=true; giu=nv=3&lv=2020-12-23T06%3A16%3A22Z&te=1; had_userid=true; anon_b2b=false; sp=sf=&cf=; last_search_parameters=family=creative&assettype=film; file_closeup_mini_adp_fileInfo=%7B%22is_sig%22%3A1%2C%22type%22%3A%22v%22%2C%22has_4k%22%3Atrue%2C%22fileId%22%3A330780182%2C%22gettyId%22%3A%221180563304%22%2C%22creditsCost%22%3A18%7D; _gat_UA-85194766-8=1; _gat_UA-000000-00=1; uac=t=OQn6AwrkMhywOiWFKzf%2BXeif7fW3BGxL%2BXKvwsxqQqiGSrJ34bgO8ZdZoaBhsbB5P6LNmXYdmnO1WHMbntZ1i3uRkKY1dqFOYjdaWiaCtMSR75zhSRrbDVdR%2BZbnsjQ1qXxGlIyZO03HozrZzlVOq7t8qo3%2BCjdnHNxySgB0Zes%3D%7C77u%2FaGpTRzVYOWRhRHhTL0Y4dXcyd0sKNDQxCjE5NzQwOTExCmtxekVFZz09Cm1yUEVFZz09CjAKZjkyMTY5YjUtYTk4NC00ZmRjLTg2NTYtOTE4MTI0MTdlZWE3CjEyNC4yOS4yMDYuMTM0CjAKNDQxCmtpSFhFZz09CjQ0MQowCmY5MjE2OWI1LWE5ODQtNGZkYy04NjU2LTkxODEyNDE3ZWVhNwoK%7C3%7C3%7C1&d=KmgQp7osfN3tfVy1aX8OieqlKwacIwW0SSFROrmtMr9Lzi2SUbKyJcoBc%2BxgyN99&i=2MgoQkrVcfs4hdsK29w%2BNOprCXpj1GeWp3S9p5eMW%2FXLdEcAhcxxHmoR%2Bdsb3eh1&hea=1; csrf=t=Ncet9JscP3sjIWqKP0%2BzIrxmCc5hbivyzZwoNFUDvPM%3D; unisess=TmVSbFBhb0pwL05uYVd0OGFiWUNTd090VHpLWlFqZUN2ajFOVHNKSnRxaHhBalFUNTlGQVovWlpRQysybmpDeDJLUm85aG5Ra05hb2FRbDdNUlZOaUE9PS0tSzVhUkJGSzlQeFRnSFk5SHhoOFZmdz09--8081f750cbdc474df8df17853e093caef4a4f449; _uetsid=c2a416c0443c11eba8b0979e55150485; _uetvid=223892103f9811eb9e80a14b5f754a27; IR_4205=1608719130728%7C0%7C1608715164671%7C%7C; IR_PI=3036ab9d-3f98-11eb-9dc6-0244022380e2%7C1608805530728; gtm_ppn=sign_in; _ga_Y8EH5J3SQE=GS1.1.1608715164.5.1.1608719136.43; _ga=GA1.1.793891982.1608120820';
        //         $headers[] = 'If-None-Match: W/\"bffcb34e32682bc19af0e8d6edcc4f98\"';
        //         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                
        //         $result = curl_exec($ch);
        //         if (curl_errno($ch)) {
        //             echo 'Error:' . curl_error($ch);
        //         }
        // curl_close($ch);
                
        //         $rows = json_decode($result,true)['items'];
        
        // foreach($rows as $row){
        //     if( $row['asset_id'] == '1191764230'  || $row['asset_id'] == '1163788101' ){
        //         continue;
        //     }
                
        //     $resource = new Resource();
            
        //     $resource->sourceable_link = "https://www.istockphoto.com".$row['landing_url'];
            
        //     $imagePhotoKeywordExists = strpos($resource->sourceable_link , "/photo/");
        //     $imageVectorKeywordExists = strpos($resource->sourceable_link , "/vector/");
        //     $videoKeywordExists = strpos($resource->sourceable_link , "/video/");
            
        //     if( $imagePhotoKeywordExists ){
        //         $resource->resource_category_id = "1";
        //     }elseif($imageVectorKeywordExists){
        //         $resource->resource_category_id = "5";
        //     }elseif($videoKeywordExists){
        //         $resource->resource_category_id = "2";
        //     }
            
        //     //$resource->resource_category_id = $row['asset_type'] == 'Film' ? '2' : '1';
        //     $resource->title = $row['image_title'];
        //     $resource->description = $row['image_title'];
        //     $resource->sourceable_type = 'iStock'; 
        //     $resource->sourceable_id   =  $row['asset_id'];
        //     $resource->keywords = [];
        //     $resource->sourceable_link = "https://www.istockphoto.com".$row['landing_url'];
        //     $resource->image = substr($row['thumb_url'],0,-7).'640x640' ;
        //     $resource->sourceable_account   = "admin@360digimarketing.com"; // admin@360digimarketing.com
        //     $resource->save();
        //     // print_r($resource);
        //     // break;
        // }
           
    
        
        
  
    }

}
