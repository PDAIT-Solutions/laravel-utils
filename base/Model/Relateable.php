<?php


namespace PDAit\Base\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * Trait Relateable
 *
 * @package PDAit\Base\Model
 */
trait Relateable
{
    use Joinable;

    private $addedSelf = false;

    /**
     * @param  Builder  $builder
     * @param         $className
     *
     * @return Builder
     */
    public function scopeAddJoin(Builder $builder, $className)
    {

        $model = $this->getExternallModel($className);
        $shortClassName = $this->getShortClassName($className);
        foreach (func_get_args() as $key => $joinedClassName) {
            if ($key > 1) {
                $joinedShortClassName = $this->getShortClassName($joinedClassName);
                $shortClassName = $joinedShortClassName.'.'.$shortClassName;
            }
        }

        $builder = $builder->withJoin($shortClassName);

        if (!$this->addedSelf) {
            foreach ($this->getModelColumnsWithoutAlias($this) as $alias => $name) {
                $builder = $builder->addSelect($name.' AS '.$alias);
            }
            $this->addedSelf = true;
        }
        foreach ($this->getModelColumnsWithAlias($model, $className, $joinedClassName) as $alias => $name) {
            $builder = $builder->addSelect($name.' AS '.$alias);
        }

        return $builder;
    }


    /**
     * @param $className
     *
     * @return Model
     */
    public function getExternallModel($className): Model
    {
        return (new $className);
    }

    /**
     * @param  Model  $model
     * @param  string  $className
     * @param  string|null  $joinedClassName
     *
     * @return array
     */
    public function getModelColumnsWithAlias(Model $model, string $className, ?string $joinedClassName = null): array
    {
        $singular = $this->getClassSingular($className);

        if ($joinedClassName) {
            $joinedSingular = $this->getClassSingular($joinedClassName);
            $singular = $joinedSingular.'_'.$singular;
        }

        $tableName = $model->getTable();
        $columns = Schema::connection($model->getConnectionName())->getColumnListing($tableName);

        $columnsAlias = [];

        array_walk(
            $columns,
            function ($v) use (&$columnsAlias, &$singular, $tableName) {
                $columnsAlias[$singular.'_'.$v] = $tableName.'.'.$v;
            }
        );

        return $columnsAlias;
    }

    /**
     * @param  Model  $model
     * @param  string  $className
     *
     * @return array
     */
    public function getModelColumnsWithoutAlias(Model $model): array
    {
        $tableName = $model->getTable();
        $columns = Schema::connection($model->getConnectionName())->getColumnListing($tableName);

        $columnsAlias = [];

        array_walk(
            $columns,
            function ($v) use (&$columnsAlias, $tableName) {
                $columnsAlias[$v] = $tableName.'.'.$v;
            }
        );

        return $columnsAlias;
    }

    /**
     * @param  string  $className
     *
     * @return string
     */
    public function getClassSingular(string $className)
    {
        if (strpos($className, '\\') !== false) {
            $singular = $this->getShortClassName($className);
        } else {
            $singular = $className;
        }

        return $this->camelCaseToSnakeCase($singular);

    }

    /**
     * @param  string  $className
     *
     * @return bool|string
     */
    public function getShortClassName(string $className)
    {
        if (strpos($className, '\\') !== false) {
            return substr($className, strrpos($className, '\\') + 1);
        }

        return $shortClassName = $className;
    }

    /**
     * @param $input
     *
     * @return string
     */
    public function camelCaseToSnakeCase($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('_', $ret);
    }

}
