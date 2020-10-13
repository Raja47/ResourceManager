<?php

namespace App\Http\Controllers\Cms;

use App\Traits\UploadAble;
use App\Models\ResourceFile;
use Illuminate\Http\Request;
use App\Contracts\ResourceContract;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use FFMpeg;
use App\Models\Image;

class FileController extends Controller
{
    use UploadAble;

    protected $resourceRepository;

    public function __construct(ResourceContract $resourceRepository)
    {
        $this->resourceRepository = $resourceRepository;
    }

    public function upload(Request $request)
    {
        $resource = $this->resourceRepository->findResourceById($request->resource_id);

        if ( $request->has('file')) {
            
            $fileName = Str::random(3).'-'.$resource->id;
            $file  = $fileName.'.'.$request->file->getClientOriginalExtension();

           $content = file_get_contents( $request->file );
           
            \Storage::disk('public')->put( 'resources/files/'.$file  , $content);

            $resourceFile = new ResourceFile([  
                'url'      =>  $file ,
                'resource_id'   => $resource->id
            ]);
        
            $resource->files()->save($resourceFile);
            
            $videoFormats = ['webm','mpg','mp2','mpeg','mpe','mpv','ogg','mp4','m4p','m4v','avi','wmv','mov','qt','flv','swf','avchd'];
            if( in_array( strtolower($request->file->getClientOriginalExtension()) , $videoFormats ) ){
                
                FFMpeg::fromDisk('public')
                    ->open('resources/files/'.$file)
                    ->getFrameFromSeconds(10)
                    ->export()
                    ->toDisk('public')
                    ->save('resources/images/original/'.$fileName.'.'.'png');

                FFMpeg::fromDisk('public')
                        ->open('resources/files/'.$file)
                        ->getFrameFromSeconds(10)
                        ->export()
                        ->toDisk('public')
                        ->save('resources/images/small/'.$fileName.'.'.'png');
                        
                $resourceImage = new Image([  
                 'url'           =>  $fileName.'.'.'png' ,
                'imageable_type' => 'App\Models\Resource',
                'imageable_id'   =>  $resource->id
                ]);
                $resource->images()->save($resourceImage);
            }
            
        }

        return response()->json(['status' => 'Success']);
    }


    public function show()
    {
        echo "hi";

      $img = asset('storage/resources/Sq7qm3nMvR3FiTr9bmaiJWtHP.jpg');

      $img = Image::make($img);



    }    


    public function delete($id)
    {
        $file = ResourceFile::findOrFail($id);

        if ($file->url != '') {
            $this->deleteOne($file->url);
        }
        $file->delete();

        return redirect()->back();
    }
}
