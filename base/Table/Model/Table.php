<?php

namespace PDAit\Base\Table\Model;

use Illuminate\Support\Collection;
use PDAit\Base\Table\Block\TableFormInterface;
use PDAit\Base\Table\Block\Thead;

/**
 * Class Table
 *
 * @package PDAit\Base\Table\Model
 */
class Table
{

    /**
     * Table constructor.
     *
     * @param string     $id
     * @param string     $url
     * @param array|null $attrs
     */
    public function __construct(string $id, string $url, ?array $attrs = [])
    {
        $this->url = $url;
        $this->attrs = $attrs;
        $this->inputs = collect();
        $this->id = $id;
        $this->theads = collect();
    }

    /**
     * @var array
     */
    private $attrs = [];
    /**
     * @var Collection
     */
    private $theads;

    /**
     * @var string
     */
    private $url;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $inputs;

    /**
     * @return string|null
     */
    public function getParamsFunc(): ?string
    {
        return $this->paramsFunc;
    }

    /**
     * @param string|null $paramsFunc
     *
     * @return Table
     */
    public function setParamsFunc(?string $paramsFunc): Table
    {
        $this->paramsFunc = $paramsFunc;

        return $this;
    }

    /**
     * @var string|null
     */
    private $paramsFunc;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     *
     * @return Table
     */
    public function setId(?string $id): Table
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @var string|null
     */
    private $id;

    /**
     * @param array $attrs
     *
     * @return Table
     */
    public function setAttrs(array $attrs): self
    {
        $this->attrs = $attrs;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttrs(): array
    {
        return $this->attrs;
    }

    /**
     * @param Thead $thead
     *
     * @return Table
     */
    public function addThead(Thead $thead): self
    {
        $this->theads->push($thead);

        return $this;
    }

    /**
     * @return Thead|null
     */
    public function getTheads(): ?Collection
    {
        return $this->theads;
    }

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Table
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function getInputs(): \Illuminate\Support\Collection
    {
        return $this->inputs;
    }

    /**
     * @param TableFormInterface $input
     */
    public function addInput(TableFormInterface $input)
    {
        $this->inputs->push($input);
    }
}
