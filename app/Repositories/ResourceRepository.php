<?php

namespace App\Repositories;

use App\Models\Resource;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use App\Contracts\ResourceContract;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use ImageInt;
/**
 * Class ResourceRepository
 *
 * @package \App\Repositories
 */
class ResourceRepository extends BaseRepository implements ResourceContract
{
    use UploadAble;

    /**
     * ResourceRepository constructor.
     * @param Resource $model
     */
    public function __construct(Resource $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function listResources(string $order = 'id', string $sort = 'desc', array $columns = ['*'])
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findResourceById(int $id)
    {
        try {
            return $this->findOneOrFail($id);

        } catch (ModelNotFoundException $e) {

            throw new ModelNotFoundException($e);
        }
    }

    /**
     * @param array $params
     * @return Resource|mixed
     */
    public function createResource(array $params)
    {
        try {
            $collection = collect($params);

           
            $status = $collection->has('status') ? 1 : 0;

            $merge = $collection->merge(compact('status'));

            $resource = new Resource($merge->all());

            $resource->save();

            // if ($collection->has('categories')) {
            //     $resource->categories()->sync($params['categories']);
            // }
            return $resource;

        } catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function updateResource(array $params)
    {
        $resource = $this->findResourceById($params['resource_id']);

        $collection = collect($params)->except('_token');

      
        $status = $collection->has('status') ? 1 : 0;

        $merge = $collection->merge(compact('status'));

        $resource->update($merge->all());

        // if ($collection->has('categories')) {
        //     $resource->categories()->sync($params['categories']);
        // }

        return $resource;
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function deleteResource($id)
    {
        $resource = $this->findResourceById($id);

        $resource->delete();

        return $resource;
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function findResourceBySlug($slug)
    {
        $resource = Resource::where('slug', $slug)->first();

        return $resource;
    }
}
