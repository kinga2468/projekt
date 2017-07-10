<?php
/**
 * Expenses
 *
 *
 * @author Kinga
 * @copyright (c) 2017
 */
namespace Model\Expenses\Arr;

/**
 * Class Categories
 */
class Expenses
{
    protected $expenses = [
        [
            'id' => 1,
            'title' => 'Makaron',
            'value' => -8,
            'budget_id' => 1,
            'categorie_id' => 2,
        ],
        [
            'id' => 2,
            'title' => 'ZUS',
            'value' => -300,
            'budget_id' => 1,
            'categorie_id' => 4,
        ],
    ];

    /**
     * Find all expenses
     *
     * @return array Result
     */
    public function findAll()
    {
        return $this->expenses;
    }

    /**
     * Find categories by its id.
     *
     * @param integer $id categories id
     *
     * @return array Result
     */
    public function findOneById($id)
    {
        $expense = [];

        if (isset($this->expenses[$id]) && count($this->expenses[$id])) {
            $expense = $this->expenses[$id];
        }

        return $expense;
    }
}
