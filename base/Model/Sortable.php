<?php


namespace PDAit\Base\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

/**
 * Trait Sortable
 *
 * @package PDAit\Base\Model
 */
trait Sortable
{

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function scopeAddSort(Builder $builder)
    {
        return $builder->orderBy(
                Request::input('sort', $builder->getModel()->getTable().'.id'),
                Request::input('order', 'DESC')
        );
    }
}