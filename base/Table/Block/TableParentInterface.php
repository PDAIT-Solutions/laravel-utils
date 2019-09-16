<?php

namespace PDAit\Base\Table\Block;

use Illuminate\Support\Collection;

/**
 * Interface TableParentInterface
 *
 * @package PDAit\Base\Table\Block
 */
interface TableParentInterface
{
    /**
     * @return Collection
     */
      function getChildren(): Collection;
}
