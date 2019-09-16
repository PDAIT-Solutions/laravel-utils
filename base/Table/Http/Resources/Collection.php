<?php

namespace PDAit\Base\Table\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class Collection
 *
 * @package PDAit\Base\Table\Http\Resources
 */
class Collection extends ResourceCollection
{
    use Singularable;

    /**
     * @var string
     */
    private $singleResource;

    /**
     * Pass additional data to resource
     *
     * @var Data
     */
    private $data;

    /**
     * Collection constructor.
     *
     * @param        $resource
     * @param string $singleResource
     * @param Data   $data
     */
    public function __construct($resource, string $singleResource, Data $data)
    {
        parent::__construct($resource);

        $this->singleResource = $singleResource;
        $this->data = $data;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data'         => $this->singularize($this->singleResource, $this->data),
            'total'        => $this->total(),
            'count'        => $this->count(),
            'per_page'     => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages'  => $this->lastPage(),
        ];
    }
}
