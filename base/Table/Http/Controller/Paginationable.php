<?php

namespace PDAit\Base\Table\Http\Controller;

use Illuminate\Database\Eloquent\Model;
use PDAit\Base\Table\Http\Resources\Collection;
use PDAit\Base\Table\Http\Resources\Data;

/**
 * Trait Paginationable
 *
 * @package PDAit\Base\Table\Http\Controller
 */
trait Paginationable
{

    /**
     * @var Model
     */
    private $model;


    /**
     * @param            $model
     * @param            $collection
     * @param array|null $collectionData
     *
     * @return Collection
     */
    public function getCollection($model, $collection, ?array $collectionData = [])
    {
        $model = $model->paginate(
            (int)request()->input('limit', 10),
            ['*'],
            'page',
            ((int)request()->input('offset', 0) +
                (int)request()->input('limit', 10)) /
            (int)request()->input('limit', 10)
        );

        $data = new Data();

        $data->setCollectionData($collectionData);

        return new Collection($model, $collection, $data);
    }

    /**
     * @param            $model
     * @param            $collection
     * @param array|null $collectionData
     *
     * @return Collection
     */
    public function getCollectionWithoutPagination($model, $collection, ?array $collectionData = [])
    {
        $model = $model->get();

        $data = new Data();

        $data->setCollectionData($collectionData);

        return new Collection($model, $collection, $data);
    }
}
