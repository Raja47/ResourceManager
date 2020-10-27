<?php

namespace App\Http\Controllers\Cms;


use Illuminate\Http\Request;
use DataTables;
use App\Contracts\ResourceContract;
use App\Contracts\CategoryContract;
use App\Http\Controllers\BaseController;
// use App\Http\Requests\StoreResourceFormRequest;
use App\Models\Resource;

class ResourceController extends BaseController
{
    

    protected $categoryRepository;

    protected $resourceRepository;

    public function __construct(
       
        ResourceContract $resourceRepository,
        CategoryContract $categoryRepository
    )
    {
       
        $this->categoryRepository = $categoryRepository;
        $this->resourceRepository = $resourceRepository;
    }

    public function index(Request $request)
    {   
        
        if ($request->ajax()) {
            $data = Resource::select(['id', 'title','resource_category_id'])->latest()->get();
            return Datatables::of($data)
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
      
        return view('admin.resources.list');
    }
    
    // public function index()
    // {
    //     $resources = $this->resourceRepository->listResources('id', 'desc');

    //     $this->setPageTitle('Resources', 'Resources List');
    //     return view('admin.resources.index', compact('resources'));
    // }

    public function create()
    {
       
        $categories = $this->categoryRepository->listCategories('title', 'asc');

        $this->setPageTitle('Resources', 'Create Resource');
        return view('admin.resources.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $params = $request->except('_token');

        $resource = $this->resourceRepository->createResource($params);

        if (!$resource) {
            return $this->responseRedirectBack('Error occurred while creating resource.', 'error', true, true);
        }
        return redirect()->route('admin.resources.edit' , ['id' => $resource->id]);
    }

    public function edit($id)
    {
        $resource    = $this->resourceRepository->findResourceById($id);
        
        $categories = $this->categoryRepository->listCategories('title', 'asc');

        $this->setPageTitle('Resources', 'Edit Resource');
        return view('admin.resources.edit', compact('categories',  'resource'));
    }

    public function update(Request $request)
    {
        $params = $request->except('_token');

        $resource = $this->resourceRepository->updateResource($params);

        if (!$resource) {
            return $this->responseRedirectBack('Error occurred while updating resource.', 'error', true, true);
        }
        return $this->responseRedirect( 'admin.resources.index' , 'Resource updated successfully' ,'success',false, false);
    }
    
    public function delete($id){
        
       $resource= $this->resourceRepository->deleteResource($id);
       
        if (!$resource) {
            return $this->responseRedirectBack('Error occurred while deleting resource.', 'error', true, true);
        }
        return $this->responseRedirect('admin.resources.index', 'Resource deleted successfully' ,'success',false, false);
    }
}