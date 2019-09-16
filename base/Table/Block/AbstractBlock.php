<?php

namespace PDAit\Base\Table\Block;

use Illuminate\Support\Collection;

/**
 * Class AbstractBlock
 *
 * @package PDAit\Base\Table\Block
 */
abstract class AbstractBlock
{

    /**
     * @var string|null
     */
    protected $text;

    /**
     * @var array
     */
    protected $attrs = [];

    /**
     * @var Collection|null
     */
    protected $children;

    /**
     * @param array $attrs
     *
     * @return mixed
     */
    abstract protected function setAttrs(array $attrs);

    /**
     * @return array
     */
    public function getAttrs(): array
    {
        return $this->attrs;
    }
}