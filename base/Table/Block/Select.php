<?php

namespace PDAit\Base\Table\Block;


/**
 * Class Select
 *
 * @package PDAit\Base\Table\Block
 */
class Select extends AbstractBlock implements TableFormInterface
{
    /**
     *
     */
    const TAG = "select";

    /**
     * @var string|null
     */
    private $name;


    /**
     * @var string|null
     */
    private $label;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $labelAttrs =[];

    /**
     * @var bool
     */
    private $nullable;

    /**
     * Select constructor.
     *
     * @param  string  $name
     * @param             $options
     * @param  string|null  $label
     * @param  array|null  $attrs
     * @param  bool  $nullable
     */
    public function __construct(
            string $name,
            $options,
            ?string $label = null,
            ?array $attrs = [],
            bool $nullable = true
    ) {
        $this->name = $name;
        $this->options = $options;
        $this->label = $label;
        $this->attrs = $attrs;
        $this->nullable = $nullable;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $options
     */
    public function setOptions($options): void
    {
        $this->options = $options;
    }

    /**
     * @param  array  $attrs
     *
     * @return Select
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
     * @param  string|null  $name
     *
     * @return Select
     */
    public function setName(?string $name): Select
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
     * @param  string|null  $label
     *
     * @return Select
     */
    public function setLabel(?string $label): Select
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getNullable(): ?bool
    {
        return $this->nullable;
    }

    /**
     * @param  bool|null  $nullable
     *
     * @return Select
     */
    public function setNullable(?bool $nullable): Select
    {
        $this->nullable = $nullable;

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