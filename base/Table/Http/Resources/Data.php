<?php


namespace PDAit\Base\Table\Http\Resources;


class Data implements \ArrayAccess, ResourceData
{
    private $loopFix = 0;

    private $collectionData = [];

    /**
     * @return array
     */
    public function getCollectionData(): array
    {
        return $this->collectionData;
    }

    /**
     * @param array $collectionData
     *
     * @return Data
     */
    public function setCollectionData(array $collectionData): Data
    {
        $this->collectionData = $collectionData;

        return $this;
    }

    /**
     * @return int
     */
    public function getLoopFix(): int
    {
        return $this->loopFix;
    }

    /**
     * @param int $loopFix
     *
     * @return Data
     */
    public function setLoopFix(int $loopFix): Data
    {
        $this->loopFix = $loopFix;

        return $this;
    }

    // KOD PONIZEJ (funkcje interfejsu \ArrayAccess) TO KOMPATYBILNOSC WSTECZNA
    /**
     * Whether a offset exists
     *
     * @link  https://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->collectionData);
    }

    /**
     * Offset to retrieve
     *
     * @link  https://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        if (array_key_exists($offset, $this->collectionData)) {
            return $this->collectionData[$offset];
        }

        return null;
    }

    /**
     * Offset to set
     *
     * @link  https://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->collectionData[$offset] = $value;
    }

    /**
     * Offset to unset
     *
     * @link  https://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->collectionData[$offset]);
    }
}
