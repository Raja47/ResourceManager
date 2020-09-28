<?php

namespace App\Http\Controllers\Cms;

use App\Traits\UploadAble;
use App\Models\ResourceFile;
use Illuminate\Http\Request;
use App\Contracts\ResourceContract;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

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
