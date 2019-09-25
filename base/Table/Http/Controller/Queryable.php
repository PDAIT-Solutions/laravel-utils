<?php

namespace PDAit\Base\Table\Http\Controller;


/**
 * Trait Queryable
 *
 * @package PDAit\Base\Table\Http\Controller
 */
trait Queryable
{
    /**
     * @param        $model
     * @param        $input
     * @param        $columns
     * @param string $check
     * @param string $columnsType
     *
     * @return mixed
     */
    public function addWhere($model, $input, $columns, $check = '=', $columnsType = 'or')
    {
        // Firstlly we check if $inputName exists in Request
        if (($input || $input === 0 || $input === '0') && $input !== '%' && $input !== '%%') {

            // Secondly we check if $columns is iterable (PHP flexity)
            if (is_iterable($columns)) {

                // Finnaly we check if $coulmnsType is 'or' or 'and' to merge wheres
                if ($columnsType == 'or') {

                    // Addding or wheres in anonymous function
                    $model = $model->where(
                        function ($query) use (&$input, &$columns, &$check) {
                            foreach ($columns as $column) {
                                $query = $query->orWhere($column, $check, $input);
                            }
                        }
                    );
                } elseif ($columnsType == 'and') {

                    // Adding wheres
                    foreach ($columns as $column) {
                        $model = $model->where($column, $check, $input);
                    }
                }
                // End of checking $columnsType

            } else {
                // Adding where
                $model = $model->where($columns, $check, $input);
            }
            //End of checking $columns

        }

        // End of checking $inputName

        return $model;
    }
}
