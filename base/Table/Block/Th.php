<?php

namespace PDAit\Base\Table\Block;

use Illuminate\Support\Collection;

/**
 * Class Th
 *
 * @package PDAit\Base\Table\Block
 */
class Th extends AbstractBlock implements  TableCellInterface
{

    const TAG = 'th';

    /**
     * TableHead constructor.
     *
     * @param string|null $text
     * @param array|null  $attrs
     */
    public function __construct(?string $text = '', ?array $attrs = [])
    {
        $this->text = $text;
        $this->attrs = $attrs;
    }

    /**
     * @param string $text
     *
     * @return Th
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param array $attrs
     *
     * @return Th
     */
    public function setAttrs(array $attrs): self
    {
        $this->attrs = $attrs;

        return $this;
    }

}