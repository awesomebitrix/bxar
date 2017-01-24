<?php

namespace marvin255\bxar\abstracts;

/**
 * Абстрактный класс, который реализует функции IQuery
 * для битриксового фильтра с логикой.
 */
abstract class QueryBitrixLogic extends Query
{
    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function andFilter(array $value)
    {
        $filter = $this->getFilter();
        if ($filter) {
            if (!empty($filter['LOGIC']) && $filter['LOGIC'] === 'AND') {
                $newFilter = $filter;
                $newFilter[] = $value;
            } elseif (!isset($filter['LOGIC']) && !isset($value['LOGIC'])) {
                $newFilter = array_merge($filter, $value);
            } else {
                $newFilter = [
                    'LOGIC' => 'AND',
                    $filter,
                    $value,
                ];
            }
        } else {
            $newFilter = $value;
        }
        $this->setFilter($newFilter);

        return $this;
    }

    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function orFilter(array $value)
    {
        $filter = $this->getFilter();
        if ($filter) {
            if (!empty($filter['LOGIC']) && $filter['LOGIC'] === 'OR') {
                $newFilter = $filter;
                $newFilter[] = $value;
            } else {
                $newFilter = [
                    'LOGIC' => 'OR',
                    $filter,
                    $value,
                ];
            }
        } else {
            $newFilter = $value;
        }
        $this->setFilter($newFilter);

        return $this;
    }
}
