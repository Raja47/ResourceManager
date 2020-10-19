<?php

namespace App\Http\Controllers\Cms;

use App\Traits\UploadAble;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Contracts\ResourceContract;
use App\Http\Controllers\Controller;
use ImageLib;
use Illuminate\Support\Str;

class ScrapController extends Controller
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


    public function show()
    {
        echo "hi";

      $img = asset('storage/resources/Sq7qm3nMvR3FiTr9bmaiJWtHP.jpg');

      $img = Image::make($img);



    }    


    public function delete($id)
    {
        $image = Image::findOrFail($id);

        if ($image->full != '') {
            $this->deleteOne($image->url);
        }
        $image->delete();

        return redirect()->back();
    }
}
