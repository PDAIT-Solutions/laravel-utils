<?php

namespace PDAit\Base\Table\Block;

use Illuminate\Support\Collection;

/**
 * Class Thead
 *
 * @package PDAit\Base\Table\Block
 */
class Thead extends AbstractBlock implements TableParentInterface
{

    const TAG = 'thead';

    /**
     * Header constructor.
     *
     * @param array|null $attrs
     */
    public function __construct(?array $attrs = [])
    {
        $this->children = collect();
        $this->attrs = $attrs;

    }

    /**
     * @param array $attrs
     *
     * @return Thead
     */
    public function setAttrs(array $attrs): self
    {
        $this->attrs = $attrs;

        return $this;
    }

    /**
     * @return array
     */
    public function getChildren(): Collection
    {
      return  $this->children;
    }

    /**
     * @param TableCellInterface $child
     */
    public function addChild(TableCellInterface $child)
    {
        $this->children->push($child);
    }
}
