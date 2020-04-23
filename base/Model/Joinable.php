<?php

/**
 * User: matteo.orefice
 * Date: 16/02/2018
 * Time: 16:57
 * test zmian
 */

namespace PDAit\Base\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * \App\MyModel::query()->select('S.*')->withJoin('relationShip1','S');
 * \App\MyModel::query()->select('P.*')->withJoin(['relationShip1','relationShip2'],'P');
 * \App\MyModel::query()->select('P.*','S.*')->withJoin(['relationShip1','relationShip2'],['P','S']);
 * \App\MyModel::query()->from(\App\MyModel::query()->getModel()->getTable().' as U')->select('U.*','S.*')->withJoin(['relationShip1','relationShip2'],['P','S']);
 * \App\MyModel::query()->from(\App\MyModel::query()->getModel()->getTable().' as U')->select('U.*','S.*')->withJoin('relationShip1.relationShip2',['P','S']);
 * \App\MyModel::query()->select('P.*','S.*')->withSelect('relationShip1.'relationShip2',['alias'=>'farcolumn']);
 * \App\MyModel::query()->select('P.*','S.*')->withSelect('relationShip1.'relationShip2',['alias'=>DB::Expr('farcolumn_expression')]);
 * \App\MyModel::query()->select('P.*','S.*')->withSelect('relationShip1.'relationShip2',[DB::Expr('farcolumn_expression')]);
 **/
trait Joinable
{
    /**
     * @param Builder              $builder
     * @param                      $relationSegments
     * @param string|string[]|null $rightAlias      se non fornito si genera a caso e vendono appesi progressivi
     *                                              se fornito come stringa diventa alias di quella piu a dx e le precedenti avranno un suffisso numerico N+1
     *                                              se fornito come array, elemento zero viene usato per la relazione piu lontana e cosi via
     * @param string               $operator
     *
     * @return $this
     * @throws \Exception
     */
    public function scopeWithJoin(
            Builder $builder,
            $relationSegments,
            $rightAlias = null,
            $decorators = null,
            $join = 'leftJoin',
            $operator = '='
    ) {
        if (is_string($decorators)) {
            if ($join === 'join') {
                $operator = '=';
            } else {
                $operator = $join;
            }
            $join = $decorators;
            $decorators = null;
        } elseif ($decorators) {
            $decorators = array_wrap($decorators);
        }

        // recupera il nome della tabella con eventuale alias dal from oppure dal nome della table corrispondente nel model
        $aliasSegments = preg_split(
                '/\s+/i',
                $previousTableAlias = $builder->getQuery()->from ?: $builder->getModel()->getTable()
        );

        // il terzo conterrebbe l'alias
        if (is_array($aliasSegments) && isset($aliasSegments[2])) {
            $previousTableAlias = $aliasSegments[2];
        }

        $this->getJoinRelationShipSubQuery(
                $this,
                $builder,
                $relationSegments,
                $previousTableAlias,
                $rightAlias,
                false,
                $decorators,
                $join,
                $operator
        );

        return $builder;
    }

    public function scopeWithSelect(
            Builder $builder,
            $relationSegments,
            $columns,
            $rightAlias = null,
            $decorators = null,
            $join = 'join',
            $operator = '='
    ) {
        if (is_string($decorators)) {
            if ($join === 'join') {
                $operator = '=';
            } else {
                $operator = $join;
            }
            $join = $decorators;
            $decorators = null;
        } elseif ($decorators) {
            $decorators = array_wrap($decorators);
        }

        // recupera il nome della tabella con eventuale alias dal from oppure dal nome della table corrispondente nel model
        $aliasSegments = preg_split(
                '/\s+/i',
                $previousTableAlias = $builder->getQuery()->from ?: $builder->getModel()->getTable()
        );
        // il terzo conterrebbe l'alias
        if (is_array($aliasSegments) && isset($aliasSegments[2])) {
            $previousTableAlias = $aliasSegments[2];
        }

        $this->getJoinRelationShipSubQuery(
                $this,
                $subQuery = DB::query(),
                $relationSegments,
                $previousTableAlias,
                $rightAlias,
                true,
                $decorators,
                $join,
                $operator
        );

        foreach ($columns as $alias => $column) {
            $subQuery->addSelect($column);
            $builder->selectSub($subQuery, is_string($alias) ? $alias : $this->wrapColumnDefinition($column));
        }

        return $builder;
    }

    protected function wrapColumnDefinition($columnDefinition)
    {
        return urldecode(
                $this->getQuery()->getGrammar()->wrap(
                        preg_replace_callback(
                                '/[^a-zA-Z]+/',
                                function ($value) {
                                    $value = $value[0];
                                    $out = '';
                                    for ($i = 0; isset($value[$i]); $i++) {
                                        $c = $value[$i];
                                        if ( ! ctype_alnum($c)) {
                                            $c = '%'.sprintf('%02X', ord($c));
                                        }
                                        $out .= $c;
                                    }

                                    return $out;
                                },
                                $columnDefinition
                        )
                )
        );
    }


    /**
     * A partire da un model crea join multiple sfruttando le relazioni
     *
     *
     *
     * @param Model $model
     * @param       $relationSegments
     * @param null  $builder
     * @param null  $previousTableAlias
     *
     * @return null
     * @throws \Exception
     */
    protected function getJoinRelationShipSubQuery(
            Model $model,
            $builder,
            $relationSegments,
            $previousTableAlias = null,
            $rightAlias = null,
            $sub = false,
            $decorators = null,
            $join = 'join',
            $operator = '='
    ) {

        $currentModel = $model;
        $relatedModel = null;
        $relatedTableAlias = null;
        $tableAliases = [];
        $relatedTableAndAlias = null;
        $relationSegments = array_wrap($relationSegments);
        if (count($relationSegments) == 1 && Str::contains($relationSegments[0], '.')) {
            $relationSegments = preg_split('/\./', $relationSegments[0]);
        }
        if (($relationIndex = count($relationSegments)) == 0) {
            throw new \Exception('Relation path cannot be empty');
        }
        /**
         * Il prefisso per le tabelle unite in JOIN viene generato a caso se non fornito
         */
        $randomPrefix = Str::random(3);
        /**
         * Per ogni segmento aggiungo una join
         */
        foreach ($relationSegments as $segment) {
            if ( ! method_exists($currentModel, $segment)) {
                throw new \BadMethodCallException("Relationship $segment does not exist, cannot join.");
            }
            $decorator = $this->getDecorator($decorators, $relationIndex);
            $relation = $currentModel->$segment();
            $relatedModel = $relation->getRelated();
            $relatedTableAlias = $this->makeTableAlias($randomPrefix, $rightAlias, $relationIndex);
            if ( ! is_null($relatedTableAlias)) {
                $tableAlias = ' AS '.$relatedTableAlias;
                $relatedTableAndAlias = $relatedModel->getTable().$tableAlias;
            } else {
                $relatedTableAndAlias = $relatedTableAlias = $relatedModel->getTable();
            }
            $tableAliases [] = $relatedTableAlias;
            /**
             * Nelle BelongsTo definiamo :
             * - CHILD TABLE(SX)    : quella dal lato con cardinalita N
             * - FOREIGN KEY        : la colonna chiave esterna sulla tabella CHILD ovvero il lato con cardinalita (N)
             * - PARENT TABLE(DX)   : quella dal lato con cardinalita 1
             * - OWNER KEY          : la colonna chiave sulla tabella PARENT ovvero il lato con cardinalita (1)
             */
            if ($relation instanceof BelongsTo) {

                if ($sub) {
                    if ( ! $previousTableAlias) {
                        throw new \RuntimeException('$previousTableAlias is required for sub');
                    }
                    $sub = false;
                    $builder
                            ->from($relatedTableAndAlias)
                            ->whereColumn(
                                    $previousTableAlias.'.'.$relation->getForeignKeyName(),
                                    $operator,
                                    $relatedTableAlias.'.'.$relation->getOwnerKeyName()
                            );
                } else {
                    $builder
                            ->$join(
                                    $relatedTableAndAlias,
                                    function (JoinClause $joinClause) use (
                                            $decorator,
                                            $previousTableAlias,
                                            $relation,
                                            $operator,
                                            $relatedTableAlias
                                    ) {

                                        $joinClause->on(
                                                $previousTableAlias ? $previousTableAlias.'.'.$relation->getForeignKeyName(
                                                        ) : $relation->getQualifiedForeignKey(),
                                                $operator,
                                                $relatedTableAlias.'.'.$relation->getOwnerKeyName()
                                        );

                                        if ($decorator instanceof \Closure) {
                                            $decorator($joinClause);
                                        }
                                    }
                            );
                }
            }
            // endif
            /**
             * Nelle HasOneOrMany definiamo :
             * - PARENT TABLE(SX)   : quella dal lato con cardinalita 1
             * - CHILD TABLE(DX)    : quella dal lato con cardinalita N
             */
            elseif ($relation instanceof HasOneOrMany) {
                if ($sub) {
                    if ( ! $previousTableAlias) {
                        throw new \RuntimeException('$previousTableAlias is required for sub');
                    }
                    $sub = false;
                    $builder
                            ->from($relatedTableAndAlias)
                            ->whereColumn(
                                    $previousTableAlias.'.'.$relation->getParent()->getKeyName(),
                                    $operator,
                                    $relatedTableAlias.'.'.$relation->getForeignKeyName()
                            );
                } else {
                    $builder
                            ->$join(
                                    $relatedTableAndAlias,
                                    function (JoinClause $joinClause) use (
                                            $decorator,
                                            $previousTableAlias,
                                            $relation,
                                            $operator,
                                            $relatedTableAlias
                                    ) {
                                        $joinClause->on(
                                                $previousTableAlias ? $previousTableAlias.'.'.$relation->getParent(
                                                        )->getKeyName() : $relation->getQualifiedParentKeyName(),
                                                $operator,
                                                $relatedTableAlias.'.'.$relation->getForeignKeyName()
                                        );
                                        if ($decorator instanceof \Closure) {
                                            $decorator($joinClause);
                                        }
                                    }
                            );
                }
            } // endif
            else {
                throw new \InvalidArgumentException(
                        sprintf("Relation $segment of type %s is not supported", get_class($relation))
                );
            } // else
            /**
             * Avanza i puntatori
             */
            $currentModel = $relatedModel;
            $previousTableAlias = $relatedTableAlias;
            $relationIndex--;
        } // end foreach <RELATIONs>

        return $tableAliases;
    }

    public function scopeWithJoinLeft(
            Builder $builder,
            $relationSegments,
            $rightAlias = null,
            $decorators = null,
            $operator = '='
    ) {
        return $this->scopeWithJoin($builder, $relationSegments, $rightAlias, $decorators, 'leftJoin', $operator);
    }

    /**
     * Crea un alias con un prefisso numerico oppure lo recupera da un array
     *
     * Se indice non e' presente prende elemento piu prossimo
     *
     * @param              $string $prefix
     * @param string|array $alias
     *
     * @return array|mixed|null|string
     */
    public function makeTableAlias($prefix, $alias, $index)
    {
        $index -= 1;
        if (is_array($alias)) {
            if (isset($alias[$index])) {
                return $alias[$index];
            }
            if ($index > 0) {
                return array_last($alias).'_'.($index);
            }

            return array_last($alias);

        } elseif ($index == 0) {
            if (is_null($alias)) {
                return null;
            }

            return $alias;
        }

        return $prefix.'_'.($index);
    }

    public function getDecorator($decorators, $index)
    {
        $index -= 1;
        if ( ! is_array($decorators)) {
            return null;
        }
        if (isset($decorators[$index])) {
            return $decorators[$index];
        }

        return null;
    }
}
