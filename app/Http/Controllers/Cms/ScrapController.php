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
        $this->token = '1/eyJjbGllbnRfaWQiOiI0ZGVlMi04Zjc3NS1kZDRjNi00ZTU2MS02ZTY0NS0xYWEwZiIsInJlYWxtIjoiY3VzdG9tZXIiLCJzY29wZSI6InVzZXIudmlldyB1c2VyLmVtYWlsIHVzZXIuYWRkcmVzcyB1c2VyLmVkaXQgb3JnYW5pemF0aW9uLnZpZXcgb3JnYW5pemF0aW9uLmFkZHJlc3MgY29sbGVjdGlvbnMudmlldyBjb2xsZWN0aW9ucy5lZGl0IGxpY2Vuc2VzLnZpZXcgbGljZW5zZXMuY3JlYXRlIG1lZGlhLnVwbG9hZCBtZWRpYS5zdWJtaXQgbWVkaWEuZWRpdCBwdXJjaGFzZXMudmlldyBwdXJjaGFzZXMuY3JlYXRlIiwidXR2IjoiWFVjMiIsInVzZXJuYW1lIjoiYWRtaW4yNzU1ODczIiwidXNlcl9pZCI6MTkzODYzOTg4LCJvcmdhbml6YXRpb25faWQiOm51bGwsIm9yZ2FuaXphdGlvbl91c2VyX2lkIjpudWxsLCJwYXJlbnRfb3JnYW5pemF0aW9uX2lkcyI6W10sImN1c3RvbWVyX2lkIjoxOTM4NjM5ODgsImV4cCI6MTYwMzY4ODc4NH0.k7F2acL8bSm5Qzjg55gUNTbnbWvsRhwD7Nj2e0vhuyPhEzwfRL-QmEAX0Twz-MXPdqZLVuQx_5q5FzA9WXJ1LQ';
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


    public function scrapss(){
        
           // $recordFile = fopen(asset("/shutterstock-resources.json",'w'));
            $records = [];
            $page_number = 1;
            
            for($page_number = 1 ;$page_number <= 245 ;$page_number++){    
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.shutterstock.com/studioapi/user/licenses?filter%5Blicense_names%5D=standard%2Cenhanced%2Ceditorial&filter%5Bmedia_type%5D=photo%2Cimage&page%5Bsize%5D=96&include=media-item%2Cproduct&page%5Bnumber%5D='.$page_number.'&filter%5Blicensee_type%5D=all&sort=newest');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                $headers = array();
                $headers[] = 'Authority: www.shutterstock.com';
                $headers[] = 'X-End-User-Ua: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36';
                $headers[] = 'Dnt: 1';
                $headers[] = 'X-End-User-Ip: 221.132.113.78';
                $headers[] = 'Authorization: Bearer '.$this->token;
                $headers[] = 'X-Shutterstock-Features: tokencide';
                $headers[] = 'X-Shutterstock-User-Token: eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzc3RrX2RhdGEiOnsiaWQiOjE5Mzg2Mzk4OCwibG9jYWxlcyI6WyJlbiIsInpoLUhhbnQiXSwiY3JlYXRlX3RpbWUiOiIyMDE4LTAyLTE5VDIxOjEyOjE3LjAwMFoifSwic3N0a19wcml2X2RhdGEiOiI5ZWVkOTdkZDFkM2YxODA5N2MwZTkzMGMzYTZiOTU0Yi5hMTcyNzAwMTYzMDQyMGVmYmE3MjJkNWQxNWU1M2EzYmNkNGM3ZjVjZDcxZjg4ZmJhNjNmMWZkYmQxNTZhMDlmYWQyNzQwYTgwOGQ4NTZhZTllYjYxN2MyOTY0Y2E3MmNlZTJmZDczMjk3YjhmYzVjMTdkOTEyMzQ2ZmU0NjdjM2I5YTAyZDQxNzA1MWQ2MTg0MDNhZmQ3NzNlNmExOWQ5NmZiMmE1YTk5ZGRiNmRhZjkyNTY2MDAzNjk3YjllZjYiLCJpYXQiOjE2MDM0MzE3NjQsImlzcyI6ImFjY291bnRzIiwic3ViIjoidXNlci4xOTM4NjM5ODgifQ.iTdNlAXkjhsr_QYbdgQ6bh84xCc-yBMxRAOa9eCubS1y1QJ_qIYXbcWV4BVu80PFN0JvLE_TCQ8D1OSQQkXT6aopY3qoKEM06HIwV-U-uraQ-r-i-y_Fd7QtDaJ4XfZkNbtQyZmaSlrd0A0FjZtABRazngyOll16Ea0yqtlIZ4Z78-GcgQDYuwkquAZ441BGQst8_BpaFY34wdzvV13BLz-ToYY4EyBe1MW-4nPPXo_X8QOOVdX-LTbFXAevIRaqAcaaw0AiH1U8Odof_WDXuZgYW6uK56bhWRmMU-mkpUVRdR0pTp-h64X8Zc9sSh5yATaYGvuIHoItH7UF4nz4B-DvKzkNf3iJPqzlSwU8a2a4LY3-nUpQofb-KUO1uMriV8cKBISM7n8w5wpyEjU_IYqAzkIEhuvQATIUJIWMWVowS-fzx6TBVuYz6tOi-SbkyuiC24RQ9YgLYvGRPq8iJx4KtX9D9Uqw97wK2vvs5fVXdToeumVAgbxcOQXjhZ0Fa8TmWwVwRqd2Exx-t6Dg3ytR5s8vUwsUk7_3tv7qGk4egSwRr0chXO1cvYUu9wkTXnsXVyzZIigeYsId7K-YgcHqLmrhc93gWVYB7O6xq1tNKjdxycFoFfiA9wUb8ZmitkQRWP5lgXfaxie34fFNzNSAb-dvPAYlGeLgY3fjC0Y';
                $headers[] = 'X-Shutterstock-Search-Id: null';
                $headers[] = 'X-End-User-Country: PK';
                $headers[] = 'X-Request-Id: c6a8ed88-fccd-47d7-b5a9-bc2311847414';
                $headers[] = 'X-End-User-Visit-Id: 68812745827';
                $headers[] = 'X-Shutterstock-Site-Section: sstk/licenses/image';
                $headers[] = 'X-End-App-Version: 0.1121.5';
                $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
                $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36';
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'X-End-App-Name: studio-web';
                $headers[] = 'Accept: application/json';
                $headers[] = 'X-End-User-Id: 193863988';
                $headers[] = 'X-End-User-Visitor-Id: 63342242511';
                $headers[] = 'Sec-Fetch-Site: same-origin';
                $headers[] = 'Sec-Fetch-Mode: cors';
                $headers[] = 'Sec-Fetch-Dest: empty';
                $headers[] = 'Referer: https://www.shutterstock.com/licenses/image';
                $headers[] = 'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8';
                $headers[] = 'Cookie: sstk_anonymous_id=%22093841f7-6166-4d0a-a246-396ab112ec71%22; visitor_id=63342242511; ajs_anonymous_id=%22093841f7-6166-4d0a-a246-396ab112ec71%22; _gcl_aw=GCL.1603106248.Cj0KCQjw8rT8BRCbARIsALWiOvT0VQ6ta7C4W75M645XLIDhxa9fBayQbfoCH9jz1ayzguJN-hyTlQYaAqDDEALw_wcB; _gcl_dc=GCL.1603106248.Cj0KCQjw8rT8BRCbARIsALWiOvT0VQ6ta7C4W75M645XLIDhxa9fBayQbfoCH9jz1ayzguJN-hyTlQYaAqDDEALw_wcB; _gcl_au=1.1.1883000201.1603106248; _cs_ex=1596812801; _cs_c=1; _ga=GA1.2.1411640632.1603106249; _gid=GA1.2.397550261.1603106249; _gac_UA-32034-1=1.1603106249.Cj0KCQjw8rT8BRCbARIsALWiOvT0VQ6ta7C4W75M645XLIDhxa9fBayQbfoCH9jz1ayzguJN-hyTlQYaAqDDEALw_wcB; __ssid=5e39bc1ab6c891420a1f1f08d480bc5; _ym_uid=1603106250486998406; _ym_d=1603106250; _ts_yjad=1603106250178; IR_gbd=shutterstock.com; C3UID=5888448081603106251; C3UID-924=5888448081603106251; __qca=P0-1705810306-1603106250679; sstk.sid=s%3AqG6egOCwBHcCUP6IdGMexol5o27iZJm1.GwMfwSaPszJytiwoDPU8BKvcwZ0qc9uVaaPIwirH4WI; ajs_anonymous_id=%22093841f7-6166-4d0a-a246-396ab112ec71%22; ei_client_id=5f8d7d8ecda4f10010139772; _biz_uid=2cdfb3d7d9464e40c2938625508ba651; _biz_flagsA=%7B%22Version%22%3A1%2C%22ViewThrough%22%3A%221%22%2C%22XDomain%22%3A%221%22%7D; _biz_nA=7; _biz_pendingA=%5B%5D; search=/search/background?; footage_search_tracking_id=88c29d41-8a95-4a09-90d6-84f3d7b991e8; _ym_isad=1; did=AZUv0AUlq4olB6baC81%2Fyo2ZPqCSM8a3R_rH; accts_customer=admin2755873; accts_customer_sso1=193863988-undefined; usi_existing_customer=1; ajs_user_id=%22193863988%22; connect.sid=s%3Ag71UXTvrFmzWqdE02eyF21oDxGm1bwaL.gtun2ptWNg4v%2Bi6bnZHIVieFon9Bn3uUuEWqJ%2BbTQ%2Fs; studioImageLHLaunch=1; locale=en-GB; _actts=1603106245.1603264134.1603271916; visit_id=68812745827; OptanonConsent=isIABGlobal=false&datestamp=Wed+Oct+21+2020+14%3A18%3A54+GMT%2B0500+(Pakistan+Standard+Time)&version=6.6.0&hosts=&consentId=2d2fc5d0-8279-41c5-a8af-247d1507418c&interactionCount=1&landingPath=NotLandingPage&groups=C0003%3A1%2CC0001%3A1%2CC0002%3A1%2CC0005%3A1%2CC0004%3A1%2CSPD_BG%3A1&AwaitingReconsent=false; IR_1305=1603271934668%7C83765%7C1603271934668%7C%7C; IR_PI=e43744cc-11fc-11eb-b289-42010a24661c%7C1603358334668; _actvc=8; AMP_TOKEN=%24NOT_FOUND; _dc_gtm_UA-32034-16=1; C3S-924=on; _dc_gtm_UA-32034-1=1; _uetsid=abf811d011fc11eb92c92fa9a1cbaee7; _uetvid=abf82c3011fc11eb80d281144a2f8cc1; _4c_=jVLLbtswEPwVg%2BfQ5kt8%2BJamQJFzWvQYSCRtCZZFgaSiuoH%2FvUvZTtL0UkEQtMPd2eHuvKK59QPaUkk4U9RUTElxhw7%2BlND2FdmxfF%2FKZ4o92qI25zFtN5t5ntepnXL2MeVgD2sbjpu%2Bs35IPm26Y7336A75AUrRGB38p5QPqcsegNraMA25gFOTbOzG3IXh%2B2ksh08foLTCK16R1f0wTHUP%2BVPy8SnXeQJ1yE7Q%2Bugj4AtfPJVmBwjrIQynY5jSowOIGK4F3SksqZRYOFLjmgmJuZF1QynzVtGFw5X%2B1KwpXTMA8m8IOSHwO8bgJpuf80Xj7JtVcqWT8y9w6ee5c7ktxVKwd7T13b7NABsmCjrGkrKm0pSnUkQLWVUajuZucGH%2BSMPf0TcaIYrOJoYZxgDxQxvh%2FistAQ1lIj%2BXggRh9Dsf45L1cWX7EPa9X5ZVpn9Zx%2Bc9Xk%2FAA58OyyDKQikvOvpg677Ug3%2BgwtuyMQi%2FsAeIv90%2F%2F3j8WpIFhesQydm6mIwSyYRB5zv06%2BI6XmlYsVLgupzBYhqSywMZsXNX%2ByFHjGu81tjVSmHhPceNkBYru7OeWr1zyw4XTkq0ZqxSYOfzRfHCAar%2Fp%2BllvBhugf3wVmzMX7XCVBX9t%2Falu%2Bn1VsLbgOd4bbGwRmHjG455o1SjG2JEJdEbJVNcCWUIu1JSfWMc%2B5v692QtQYNU8pYsrv3P5z8%3D; _actcc=6.3.188.72; _actmu=92049eb2-1122-4f18-b318-e9bbaa952f86; _actms=9abebe3b-92de-4596-b3e6-468c35e2f3e3';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                $rows = json_decode($result,true)['included'];
                
                curl_close($ch);
                
              
                
                foreach($rows as $row){
                    
                    
                    if($row['type'] == 'products'){
                        continue;
                    }
                    $record = [];
                    $record['content_id']   = $row['id'];  
                    $record['title']        = $row['attributes']['title'] ? $row['attributes']['title'] : "" ;
                    $record['description']  = $row['attributes']['keywords'] ? $row['attributes']['keywords'] : "";
                    $record['keywords']     = $row['attributes']['description'] ? $row['attributes']['description'] : "";
                    $record['image']        = $row['attributes']['src'] ? $row['attributes']['src'] : "";
                    $record['link']         = $row['attributes']['link'] ? $row['attributes']['link'] : "";
                    // $records[] = $record; 
                    file_put_contents("/home/eworldclients/public_html/demo/search/public/shutterstock-resources.json", json_encode($record), FILE_APPEND);
                }
               
            }
           
            echo "file write successfully";
            
                
            // var_dump($result);
            // $result = $this->scrapDownlaod('745069222');
            // var_dump($result);
          
    }
    public function scraps(){
        
        
        $resources = Resource::all();
        
        // foreach( $resources as $resource ){
        //     $keywords  = $resource->keywords;
        //     $keywords[] = "All";
        //     $resource->keywords = $keywords;
        //     $resource->save();
        // }
        
        //   // $recordFile = fopen(asset("/shutterstock-resources.json",'w'));
        //     $records = [];
        //     $page_number = 1;
            
        //     for($page_number = 1 ;$page_number <= 4 ;$page_number++){    
                
        //         // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        //         $ch = curl_init();
                
        //         curl_setopt($ch, CURLOPT_URL, 'https://www.shutterstock.com/studioapi/user/licenses?filter%5Bmedia_type%5D=video&page%5Bsize%5D=96&include=media-item%2Cproduct&page%5Bnumber%5D='.$page_number.'&filter%5Blicensee_type%5D=all&sort=newest');
        //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                
        //         curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                
        //         $headers = array();
        //         $headers[] = 'Authority: www.shutterstock.com';
        //         $headers[] = 'Sec-Ch-Ua: \"Chromium\";v=\"86\", \"\"Not\\A;Brand\";v=\"99\", \"Google Chrome\";v=\"86\"';
        //         $headers[] = 'X-End-User-Ua: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Safari/537.36';
        //         $headers[] = 'Dnt: 1';
        //         $headers[] = 'X-End-User-Ip: 124.29.206.134';
        //         $headers[] = 'Authorization: Bearer 1/eyJjbGllbnRfaWQiOiI0ZGVlMi04Zjc3NS1kZDRjNi00ZTU2MS02ZTY0NS0xYWEwZiIsInJlYWxtIjoiY3VzdG9tZXIiLCJzY29wZSI6InVzZXIudmlldyB1c2VyLmVtYWlsIHVzZXIuYWRkcmVzcyB1c2VyLmVkaXQgb3JnYW5pemF0aW9uLnZpZXcgb3JnYW5pemF0aW9uLmFkZHJlc3MgY29sbGVjdGlvbnMudmlldyBjb2xsZWN0aW9ucy5lZGl0IGxpY2Vuc2VzLnZpZXcgbGljZW5zZXMuY3JlYXRlIG1lZGlhLnVwbG9hZCBtZWRpYS5zdWJtaXQgbWVkaWEuZWRpdCBwdXJjaGFzZXMudmlldyBwdXJjaGFzZXMuY3JlYXRlIiwidXR2IjoiWFVjMiIsInVzZXJuYW1lIjoiYWRtaW4yNzU1ODczIiwidXNlcl9pZCI6MTkzODYzOTg4LCJvcmdhbml6YXRpb25faWQiOm51bGwsIm9yZ2FuaXphdGlvbl91c2VyX2lkIjpudWxsLCJwYXJlbnRfb3JnYW5pemF0aW9uX2lkcyI6W10sImN1c3RvbWVyX2lkIjoxOTM4NjM5ODgsImV4cCI6MTYwNTA3NDUzNn0.VJWqmnveLcpw9XYDKzJaJI3ehUyxb_qtw1uCYu1cPkOMc_FBG9KU7EAH1CrXN9SDQcmfj_BqOvthtBHXZaJ6Kg';
        //         $headers[] = 'X-Shutterstock-Features: tokencide';
        //         $headers[] = 'X-Shutterstock-User-Token: eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzc3RrX2RhdGEiOnsiaWQiOjE5Mzg2Mzk4OCwibG9jYWxlcyI6WyJlbiIsInpoLUhhbnQiXSwiY3JlYXRlX3RpbWUiOiIyMDE4LTAyLTE5VDIxOjEyOjE3LjAwMFoifSwic3N0a19wcml2X2RhdGEiOiIyNDg3NmE5YzE3OGM5NmM0MGRiNGY0YWEwZjVlZjQ5Mi4wZDM1MjhlZjhjMTE3ZmU4NDYwZWU5ZWE1OGY3ZGMzNWVhYmQwYzY0NzIxY2IxMTA2YmQ1NDQ1YmNkOWFjODBlNGZkNDdiYTMyNDhkMjlmODYwOTJiODdhNzljNGExMGRiMDI5ZTI4YzE0ODNjNDA2Mzk5ZmMwMGQyYjAyZTk5NjhjNmRiNDBkNDgyNGIyMWYxNzI0ZDc4NWJjNjZkZWY3NzE3ODFhNmEwZWY2YzUzZWQ0NWUzOTY4MmQ5OGEyOTYiLCJpYXQiOjE2MDM4NjU0NjEsImlzcyI6ImFjY291bnRzIiwic3ViIjoidXNlci4xOTM4NjM5ODgifQ.eKWnKVZ6hetDohU3RliC_U6P3coChtSypm1dsS1AKgD7QEovR-G0vhDYoQ0AWjjpkhGKQMfEA4aeKrC_5fYUxQ0tRMygsSWglkBb6SC2o_ph33q_ErIGVzhBLJ8eK5Nd5S29Bg06ViQ9w2zRQHiSxZQsyK8ItNTBB3p-NCsaxKQKRSghGJR5mC9bOEnhAxL41p56Z06oM3L_vAaCupJ6o7TXCFtJSsZbE6w2UCqyScuV5s2H29NXd_G5c7upBBUGe6M-J7PwEupthophL1Pu_Tzkdts47YliU0IGpCNjIjADpd9Ydgh6LZanaipu9gKCN3Nol0ii00FGGQXr9hNEkepX8eeHkm4HoknogdmCd--IPBY6StQ772IM4uupg6fMvjnsIjjhJmRHn6_anv41-ynmWUHz2tmGQSt16WTwWRIklNC1wzF1CRKy4ZyGXJpUpssO65ncCt1_w9pf-WU6x1oqUdA411AC_W0fiKFyYca-dm7sThkkoZEbto1cTt_FX8i4UX9WftHr2cm5T0RCjyH8ohbnbP4LhK0FZP7TBCF4I8kMIwNmWqVFQZYxFkrsL9veLf1T5FYHadsYi1yReWahPIQ8UIS_YU6ZnmsGCfYAj_MS2kv2IrxhaDFBtjRNGX2GwbneiyvO9PsA4k4nAHkVrpoB5Rxv2Lu6-rnpoKY';
        //         $headers[] = 'X-Shutterstock-Search-Id: null';
        //         $headers[] = 'X-End-User-Country: PK';
        //         $headers[] = 'X-Request-Id: fc2611ca-97ad-4e23-9143-77cce3909cfa';
        //         $headers[] = 'X-End-User-Visit-Id: 68880095151';
        //         $headers[] = 'X-Shutterstock-Site-Section: sstk/licenses/video';
        //         $headers[] = 'X-End-App-Version: 0.1124.2';
        //         $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        //         $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Safari/537.36';
        //         $headers[] = 'Content-Type: application/json';
        //         $headers[] = 'X-End-App-Name: studio-web';
        //         $headers[] = 'Accept: application/json';
        //         $headers[] = 'X-End-User-Id: 193863988';
        //         $headers[] = 'X-End-User-Visitor-Id: 63342242511';
        //         $headers[] = 'Sec-Fetch-Site: same-origin';
        //         $headers[] = 'Sec-Fetch-Mode: cors';
        //         $headers[] = 'Sec-Fetch-Dest: empty';
        //         $headers[] = 'Referer: https://www.shutterstock.com/licenses/video';
        //         $headers[] = 'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8';
        //         $headers[] = 'Cookie: sstk_anonymous_id=%22093841f7-6166-4d0a-a246-396ab112ec71%22; visitor_id=63342242511; ajs_anonymous_id=%22093841f7-6166-4d0a-a246-396ab112ec71%22; _gcl_aw=GCL.1603106248.Cj0KCQjw8rT8BRCbARIsALWiOvT0VQ6ta7C4W75M645XLIDhxa9fBayQbfoCH9jz1ayzguJN-hyTlQYaAqDDEALw_wcB; _gcl_dc=GCL.1603106248.Cj0KCQjw8rT8BRCbARIsALWiOvT0VQ6ta7C4W75M645XLIDhxa9fBayQbfoCH9jz1ayzguJN-hyTlQYaAqDDEALw_wcB; _gcl_au=1.1.1883000201.1603106248; _cs_ex=1596812801; _cs_c=1; _ga=GA1.2.1411640632.1603106249; _gac_UA-32034-1=1.1603106249.Cj0KCQjw8rT8BRCbARIsALWiOvT0VQ6ta7C4W75M645XLIDhxa9fBayQbfoCH9jz1ayzguJN-hyTlQYaAqDDEALw_wcB; __ssid=5e39bc1ab6c891420a1f1f08d480bc5; _ym_d=1603106250; _ym_uid=1603106250486998406; _ts_yjad=1603106250178; C3UID-924=5888448081603106251; C3UID=5888448081603106251; __qca=P0-1705810306-1603106250679; sstk.sid=s%3AqG6egOCwBHcCUP6IdGMexol5o27iZJm1.GwMfwSaPszJytiwoDPU8BKvcwZ0qc9uVaaPIwirH4WI; ajs_anonymous_id=%22093841f7-6166-4d0a-a246-396ab112ec71%22; ei_client_id=5f8d7d8ecda4f10010139772; _biz_uid=2cdfb3d7d9464e40c2938625508ba651; _biz_flagsA=%7B%22Version%22%3A1%2C%22ViewThrough%22%3A%221%22%2C%22XDomain%22%3A%221%22%7D; _biz_nA=7; _biz_pendingA=%5B%5D; did=AZUv0AUlq4olB6baC81%2Fyo2ZPqCSM8a3R_rH; accts_customer=admin2755873; accts_customer_sso1=193863988-undefined; ajs_user_id=%22193863988%22; connect.sid=s%3Ag71UXTvrFmzWqdE02eyF21oDxGm1bwaL.gtun2ptWNg4v%2Bi6bnZHIVieFon9Bn3uUuEWqJ%2BbTQ%2Fs; IR_gbd=shutterstock.com; _fbp=fb.1.1603433582666.647222269; footage_search_tracking_id=3eddc1d6-6361-4a8f-b983-8d7758511a39; search=/search/new+images?; visit_id=68880095151; _actts=1603106245.1603770836.1603861469; _gid=GA1.2.1602045383.1603861470; _actvc=19; _ym_isad=1; usi_existing_customer=1; locale=en-GB; OptanonConsent=isIABGlobal=false&datestamp=Wed+Oct+28+2020+11%3A02%3A03+GMT%2B0500+(Pakistan+Standard+Time)&version=6.6.0&hosts=&consentId=2d2fc5d0-8279-41c5-a8af-247d1507418c&interactionCount=1&landingPath=NotLandingPage&groups=C0003%3A1%2CC0001%3A1%2CC0002%3A1%2CC0005%3A1%2CC0004%3A1%2CSPD_BG%3A1&AwaitingReconsent=false; IR_1305=1603863427922%7C83765%7C1603861469736%7C%7C; IR_PI=e43744cc-11fc-11eb-b289-42010a24661c%7C1603949827922; _uetsid=0f30354018db11ebba0af94b4f8b1c00; _uetvid=abf82c3011fc11eb80d281144a2f8cc1; AMP_TOKEN=%24NOT_FOUND; _4c_=jVK5jtswEP0Vg7Up8z7cbTZAsPUmSGlIFB0JliWBpKw4hv89Qx%2FrjauwkebNzJvrndDc%2BB6tqSLcKMk1t1Yu0c4fI1qfUGjr%2FDmgNaoUqYznBmstGRa1MLgyymDvaUlozZzkFVqi35mLUka0MoxKfV4iN944TmgKHVA1KY1xvVrN81zEZkrJh5gGtyvcsF91rfN99HF1aGs%2FAKPvc%2Fkx1PAfY9rFNnkASueGqU8ZnKroQjumdui%2FH8fsfP8ExQVecEkWL30%2FlR3ET9GH91SmCUZEboLSex8Av%2FCFYy62A7Psh%2F64H6b4BjtAxHIj6FZjRZWC8UmJSyYU5laVFczrnaYXjjrXp7agtGAApD9gckLgdwxDPbm0SdceZ18tYp0r1f4AQ2%2Fmtk5NTlaCPdDGt7%2BaBLBlIqNjyCEFVTY%2FqYkRSkoDrrnt62H%2BTMMf6AeN5hLQKgwzrAHs1ybA%2FAujAB3yRn5eEiKYwW99CJeovObr3p8PdvOAYp6ceeJ8OZZn7wZXdjkdxAYJ3uXLgPmFvYL97WXz4%2B1r7llQaJsozoqsSEoUExadb7Ii3EqqNSNUw2ITSMlAcH4QcWjvSuVOaKYZw9KRGgtKNK62cDnNXaUF5RV7KDWrXlOQKuM3SmrujGN3Y6SPYC2NskbYe7D4qD8ebtGM%2FE%2B71wNgmB%2F7%2FiPX%2FpMKbQnxnHo%2B%2FwU%3D; _actcc=39.12.438.173; _actmu=92049eb2-1122-4f18-b318-e9bbaa952f86; _actms=aabc7db3-db83-4326-9aed-d6b7b3ab9fa4';
        //         $headers[] = 'If-None-Match: W/\"4903c-liKoJoKLJnViywHC6T4pnGwv7Eo\"';
        //         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                
        //         $result = curl_exec($ch);
        //         if (curl_errno($ch)) {
        //             echo 'Error:' . curl_error($ch);
        //         }
        //         curl_close($ch);
        //         $rows = json_decode($result,true)['included'];
        //         foreach($rows as $row){
                    
        //             if($row['type'] == 'products'){
        //                 continue;
        //             }
        //             $record = [];
        //             $record['sourceable_type'] = "shutterstock";    
        //             $record['sourceable_id']   = $row['id'];
        //             $record['resource_category_id']   = '2';       
        //             $record['title']        = $row['attributes']['description'] ? $row['attributes']['description'] : "" ;
        //             $record['description']  = $row['attributes']['description'] ? $row['attributes']['description'] : "";
        //             $record['keywords']     = $row['attributes']['keywords'] ?  implode( ';' , $row['attributes']['keywords']) : "";
        //             $record['image']        = $row['attributes']['preview_image_url'] ? $row['attributes']['preview_image_url'] : "";
        //             $record['preview_video_url'] = $row['attributes']['preview_video_urls']['mp4'] ? $row['attributes']['preview_video_urls']['mp4'] : '' ; 
        //             file_put_contents("/home/eworldclients/public_html/demo/search/public/shutterstock-resources-videos.json", json_encode($record), FILE_APPEND);
        //         }
               
        //     }
           
            // echo "file write successfully";
                
            // var_dump($result);
            // $result = $this->scrapDownlaod('745069222');
            // var_dump($result);
    }
    // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/

    
    public function scrapDownload(){
         
        $resources = Resource::where('resource_category_id','1')->where('creator_id',null)->where('sourceable_download_link','!=', null)->get()->take(225);
        foreach($resources as $resource){
                $link    = $resource->sourceable_download_link ;
                $contents= file_get_contents($link);
                file_put_contents('/home/eworldclients/public_html/demo/library/storage/app/public/resources/images/original/shutterstock_'.$resource->sourceable_id.'.jpg', $contents);
                $resource->creator_id = '1';
                $resource->save();
         }        
          
        // $resources = Resource::where('resource_category_id','1')->where('sourceable_download_link', null)->get()->take(1000);
        // foreach($resources as $resource){
            
        //     // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        //     $ch = curl_init();
            
        //     curl_setopt($ch, CURLOPT_URL, 'https://www.shutterstock.com/studioapi/licensees/current/redownload');
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //     curl_setopt($ch, CURLOPT_POST, 1);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"content\":[{\"content_format\":\"jpg\",\"content_id\":\"".$resource->sourceable_id."\",\"content_size\":\"huge\",\"content_type\":\"photo\",\"license_name\":\"standard\",\"show_modal\":true}],\"country_code\":\"PK\",\"required_cookies\":\"\"}");
        //     curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            
        //     $headers = array();
        //     $headers[] = 'Authority: www.shutterstock.com';
        //     $headers[] = 'X-End-User-Ua: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.66 Safari/537.36';
        //     $headers[] = 'Dnt: 1';
        //     $headers[] = 'X-End-User-Ip: 110.93.232.46';
        //     $headers[] = 'Authorization: Bearer 1/eyJjbGllbnRfaWQiOiI0ZGVlMi04Zjc3NS1kZDRjNi00ZTU2MS02ZTY0NS0xYWEwZiIsInJlYWxtIjoiY3VzdG9tZXIiLCJzY29wZSI6InVzZXIudmlldyB1c2VyLmVtYWlsIHVzZXIuYWRkcmVzcyB1c2VyLmVkaXQgb3JnYW5pemF0aW9uLnZpZXcgb3JnYW5pemF0aW9uLmFkZHJlc3MgY29sbGVjdGlvbnMudmlldyBjb2xsZWN0aW9ucy5lZGl0IGxpY2Vuc2VzLnZpZXcgbGljZW5zZXMuY3JlYXRlIG1lZGlhLnVwbG9hZCBtZWRpYS5zdWJtaXQgbWVkaWEuZWRpdCBwdXJjaGFzZXMudmlldyBwdXJjaGFzZXMuY3JlYXRlIiwidXR2IjoiWFVjMiIsInVzZXJuYW1lIjoiYWRtaW4yNzU1ODczIiwidXNlcl9pZCI6MTkzODYzOTg4LCJvcmdhbml6YXRpb25faWQiOm51bGwsIm9yZ2FuaXphdGlvbl91c2VyX2lkIjpudWxsLCJwYXJlbnRfb3JnYW5pemF0aW9uX2lkcyI6W10sImN1c3RvbWVyX2lkIjoxOTM4NjM5ODgsImV4cCI6MTYwNjkwMTM1M30.J0v_SxJ8k1RC85dGjqhSL2wEsIziFX_2GCrW-p_exF7mUJhmBb_E0ed5Ol0D7_Vqfv5xFdlyEpNXpqKFeEqJsA';
        //     $headers[] = 'X-Shutterstock-Features: tokencide';
        //     $headers[] = 'X-Shutterstock-User-Token: eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzc3RrX2RhdGEiOnsiaWQiOjE5Mzg2Mzk4OCwibG9jYWxlcyI6WyJlbiIsInpoLUhhbnQiXSwiY3JlYXRlX3RpbWUiOiIyMDE4LTAyLTE5VDIxOjEyOjE3LjAwMFoifSwic3N0a19wcml2X2RhdGEiOiI3N2U4MzU4MDBkY2Y2OGJmNTBiNzI1MWVlNmQ5N2FkYi5lNzNkMjg5ZDA1OGExM2Y4ZmU1NzU0MTY1YWFiYzZkZjg0YzI0YWZlYjA4YTljMGVhNTM5YjRiNzBiMzQ1Njk4YWNhMmExYmZiZDdhMjc2Y2ZjNDM1YThlMDdmN2I3MzIwZmNlMTkxODhmZGI5OGMyNmQ0N2RhYjljNDM2MGUwZjBiYmJlOWRkYjdkOWIyZGNhOGMzNThkMDUwZTVhMzcxMmM4MzEwNTg4NTY1Y2FkNjJlNWIyOTA0NGMwMWU4ODEiLCJpYXQiOjE2MDY4OTc3NTMsImlzcyI6ImFjY291bnRzIiwic3ViIjoidXNlci4xOTM4NjM5ODgifQ.oRIWhaO5ql1qaKweUzVspZUTgm8_CNYJl-locMKaNKBul9KuWiOdbBQrI1zAlLipy0v5s4mREUUkWAG43gjJYnVticSSIY59DkQPp9Z7NEi1oyGMBRCTeoCbVecslfOTpx_ssZqZM6AL8jlJi0GbiCPALg_nv9A70wvddrZItBMcnQvczbSbAxg43s62J3ixqPvO5eSguTDqPmieTQEHz1FrkQ8Ec05VkqK39GFUGOFeISOuCGZJPN5l6iTWfZXNmjrFwRW67kbNCihOcvZm3tVImT-NeIrzMrgMUa4kWlzXPNVYlG-FmKtxn50No1ihCkQ_5jsodqljAWgx9FHUqU08HNO6GCrSuZVhXzBQeEZsO_cdThzmZ6LmOk521JU5a3XfDr4QnwfT5qBRkGP2RrQ1-J4wW8UHS36AXmqImeJbXvm7nrDb9Rv7DelWPVPNLKEMvDxps0GnN1p9mG-hh5_fU9N5jz0aNQKIV-s6Gbw_KrOdchmBBhPrV3ZbcAnMn7LYYvb7_In_NE1YfTMkruAqMeL5pNh3c-UGpWKGHhZCCCt3I3OztLYF7T5SG2XGwZQapGyvkQ7qiJ2gofQASqS7U2JdXsb091hSSvY2rgjh7cpxhRDxGegY05GqpR4bxov9q-chxW2Jsgn0gaEn57iKnqyJMf9ylooA_SvwZx0';
        //     $headers[] = 'X-Shutterstock-Search-Id: null';
        //     $headers[] = 'X-End-User-Country: PK';
        //     $headers[] = 'X-Request-Id: 6e9b0e33-b881-42d0-aa1e-58514e70834d';
        //     $headers[] = 'X-End-User-Visit-Id: 69464151918';
        //     $headers[] = 'X-Shutterstock-Site-Section: sstk/licenses/image';
        //     $headers[] = 'X-End-App-Version: 0.1130.14';
        //     $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.66 Safari/537.36';
        //     $headers[] = 'Content-Type: application/json';
        //     $headers[] = 'X-End-App-Name: studio-web';
        //     $headers[] = 'Accept: application/json';
        //     $headers[] = 'X-End-User-Id: 193863988';
        //     $headers[] = 'X-End-User-Visitor-Id: 63848983170';
        //     $headers[] = 'Origin: https://www.shutterstock.com';
        //     $headers[] = 'Sec-Fetch-Site: same-origin';
        //     $headers[] = 'Sec-Fetch-Mode: cors';
        //     $headers[] = 'Sec-Fetch-Dest: empty';
        //     $headers[] = 'Referer: https://www.shutterstock.com/licenses/image';
        //     $headers[] = 'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8';
        //     $headers[] = 'Cookie: visitor_id=63848983170; did=5745bd6b-3bc0-475b-a042-992e92bdc924; connect.sid=s%3ANEsM_pPmq6BruyAh-3KgPk6O9-vWnJ0J.mSCdhXOKBvYXCV3eQ%2FLbtbxnTe6gJw1zHbgn4%2FRy7nI; sstk.sid=s%3AuGCHvBSLHu1gRIf9p_dmhp0Nnk1Y9ZYW.LLGwOCHjyT85yyGqDdhrtW94hLlPUcfF7TK%2FJyCnYrY; ajs_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; __ssid=2df00e6272821102261db2c18419ea5; optimizelyEndUserId=oeu1606457031873r0.113129512866039; _gcl_au=1.1.1393381827.1606457034; accts_customer=admin2755873; accts_customer_sso1=193863988-undefined; C3UID-924=4120151631606457035; C3UID=4120151631606457035; sstk_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; IR_gbd=shutterstock.com; _ts_yjad=1606457049145; __qca=P0-661513937-1606457048890; _ga=GA1.2.2075455506.1606457070; ajs_anonymous_id=%221e7d5a12-48a8-4bbc-a5d4-27812885fd4b%22; ajs_user_id=%22193863988%22; _cs_ex=1596812801; _cs_c=1; _ym_uid=1606709788437414803; _ym_d=1606709788; usi_existing_customer=1; usi_analytics=a_3kb80s_1606709789_5698; _gid=GA1.2.1995304092.1606883349; _actts=1606457047.1606886063.1606897755; visit_id=69464151918; locale=en-GB; OptanonConsent=isIABGlobal=false&datestamp=Wed+Dec+02+2020+13%3A29%3A26+GMT%2B0500+(Pakistan+Standard+Time)&version=6.6.0&hosts=&consentId=a6b045c0-2232-4a78-93be-dd3d9115dcce&interactionCount=1&landingPath=https%3A%2F%2Fwww.shutterstock.com%2Flicenses%2Fimage&groups=C0003%3A1%2CC0001%3A1%2CC0002%3A1%2CC0005%3A1%2CC0004%3A1%2CSPD_BG%3A1; _cs_mk=0.2971812087442778_1606897766254; _uetsid=eb30e800345611eb8b21599d57b71fc1; _uetvid=5d29a3a0307611eb926f21f42ecb5121; IR_1305=1606897766467%7C83765%7C1606897766467%7C%7C; IR_PI=5d219c2b-3076-11eb-8648-42010a24661c%7C1606984166467; AMP_TOKEN=%24NOT_FOUND; _actvc=4; C3S-924=on; _actcc=4.1.16.4; _4c_=jVJNj9sgEP0rEeeQAMYYcmv3VKm37X2FgaytOMbiI940yn%2FvkHiT1Z7qi2ceM%2B8xw7uguXMj2lFBhFSS1BVp5Bod3Dmi3QWF3pbfCe2QEsa4pm7xXmiJecUaLGvDsKi0YsIqURuO1uijcFHOqopwKWt5XSMzLRwXlMMAVF1KU9xtt%2FM8b2KXU3IhJm8OG%2BOP26E3bowubvujfnfA6MYiPwULcYzpEPvkANDG%2BDymAuY2mtBPqffjn%2FNUDl%2B%2FQHGFV1VNVj%2FGMesB6nN04TXplGFEZDJIH10A%2FMYXzkXsAKke%2FXg%2B%2Bhx%2FwQ4QdY2tNWWYyzJ%2B2xqsa8sxayRlMOje8vbGYYs%2BVRtKNwyA9BfSihAIp%2BBtNukt3e84u3YVbVGy7gRDv829TV1pFpI80c71710qMKlv8BRKAtHcj9bPX9qIfKKPNslLbRv8DGND%2FtIFmHclG0B92cDvfswfkAS3dyHcaspS71v%2B%2FjzLCfjj22GZr7yTgGDwRg%2BlG5wF9c6UZ4D0J3tB18UiRCi4mRCKMlhSAltIwUn5rnemm2PE%2F1Sf%2Bk%2BP1pQaLZ3GBFowZ9JhyWWDqWpa8C5pjCXoQSmVqoUQVCyUVD70h4WRPvUpA33B1GcxX%2FSv138%3D; _gat_UA-32034-16=1; _actmu=ee8b9134-5946-4f13-9e56-0844fe5f9346; _actms=69147220-40b3-4288-975d-adfdbb1858e5';
        //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
        //     $result = curl_exec($ch);
        //     if (curl_errno($ch)) {
        //     echo 'Error:' . curl_error($ch);
        //     }
        //     curl_close($ch);
            
        //     if($result){
        //         $download_url = json_decode($result,true)['meta']['licensed_content'][0]['download_url'];
        //         $resource->sourceable_download_link = $download_url;
        //         $resource->save();
        //     }
           
        // } 
        
    }

}
