<?php


namespace PDAit\Base\Table\Http\Resources;


interface ResourceData
{
    /**
     * To jest trzeci argument  $this->getCollection($model, ExampleCollection::class, $data);
     *
     * @return array
     */
    public function getCollectionData(): array;

    /**
     * Zwraca offset + lp.
     *
     * @return int
     */
    public function getLoopFix(): int;
}
