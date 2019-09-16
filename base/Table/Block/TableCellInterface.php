<?php

namespace PDAit\Base\Table\Block;

/**
 * Interface TableCellInterface
 *
 * @package PDAit\Base\Table\Block
 */
interface TableCellInterface
{
    /**
     * @param string $text
     *
     * @return mixed
     */
    public function setText(string  $text);

    /**
     * @return string|null
     */
    public function getText(): ?string;
}
