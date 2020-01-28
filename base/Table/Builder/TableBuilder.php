<?php

namespace PDAit\Base\Table\Builder;

use PDAit\Base\Table\Block\Thead;
use PDAit\Base\Table\Model\Table;

/**
 * Trait TableBuilder
 *
 * @package PDAit\Base\Table\Builder
 */
trait TableBuilder
{

    /**
     * @var Table|null
     */
    protected $table;
    /**
     * @var Thead|null
     */
    protected $thead;

    /**
     * @return Thead
     */
    protected function prepare($id, $url): Thead
    {
        $this->table = new Table($id, $url);
        $this->thead = new Thead();
        $this->table->addThead($this->thead);

        return $this->thead;
    }

    /**
     * @return Table
     */
    protected function getTable(): Table
    {
        return $this->table;
    }
}
