<?php

namespace App\Contracts;

/**
 * Interface ResourceContract
 * @package App\Contracts
 */
interface ResourceContract
{
    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function listResources(string $order = 'id', string $sort = 'desc', array $columns = ['*']);

    /**
     * @param int $id
     * @return mixed
     */
    public function findResourceById(int $id);

    /**
     * @param array $params
     * @return mixed
     */
    public function createResource(array $params);

    /**
     * @param array $params
     * @return mixed
     */
    public function updateResource(array $params);

    /**
     * @param $id
     * @return bool
     */
    public function deleteResource($id);

}
