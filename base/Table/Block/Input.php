<?php

namespace PDAit\Base\Table\Block;


/**
 * Class Input
 *
 * @package PDAit\Base\Table\Block
 */
class Input extends AbstractBlock implements TableFormInterface
{
    /**
     *
     */
    const TAG = "input";

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $label;
    /**
     * @var array
     */
    private $labelAttrs =[];


    /**
     * Input constructor.
     *
     * @param string      $name
     * @param string|null $label
     * @param array|null  $attrs
     */
    public function __construct(string $name, ?string $label = null, ?array $attrs = [])
    {
        $this->name = $name;
        $this->label = $label;
        $this->attrs = $attrs;
    }

    /**
     * @param array $attrs
     *
     * @return Input
     */
    public function setAttrs(array $attrs): self
    {
        $this->attrs = $attrs;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return Input
     */
    public function setName(?string $name): Input
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string|null $label
     *
     * @return Input
     */
    public function setLabel(?string $label): Input
    {
        $this->label = $label;

        return $this;
    }
    /**
     * @param  array|null  $labelAttrs
     * @return $this
     */
    public function setLabelAttrs(?array $labelAttrs)
    {
        $this->labelAttrs = $labelAttrs;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabelAttrs()
    {
        return $this->labelAttrs;
    }

}