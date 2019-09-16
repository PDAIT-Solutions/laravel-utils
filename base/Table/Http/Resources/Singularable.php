<?php

namespace PDAit\Base\Table\Http\Resources;

/**
 * Trait Singularable
 *
 * Inspired by: https://medium.com/@dinotedesco/laravel-api-resources-what-if-you-want-to-manipulate-your-models-before-transformation-8982846ad22c
 *
 * @package PDAit\Base\Table\Resources
 */
trait Singularable
{
    /**
     * @var int
     */
    private $loopFixKey = 1;

    /**
     * @param string $resource
     * @param Data   $data
     *
     * @return mixed
     */
    public function singularize(string $resource, Data $data)
    {
        $this->collection->transform(
            function ($obj) use (&$data, &$resource) {
                $data = clone $data;
                $data->setLoopFix($this->loopFixKey + request()->offset);
                $this->loopFixKey++;

                return (new $resource($obj, $data));
            }
        );

        return $this->collection;
    }
}
