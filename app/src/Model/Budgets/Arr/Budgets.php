<?php
/**Budgets*/
namespace Model\Budgets\Arr;

/**
 * Class Budgets
 */
class Budgets
{
    protected $budgets = [
        [
            'id' => 0,
            'title' => 'Maj 2017',
            'date_from' => '01.05.2017',
            'date_to' => '31.05.2017',
            'limit' => 3000,
            'users_id' => 1,

        ],
        [
            'id' => 1,
            'title' => 'Czerwiec 2017',
            'date_from' => '01.06.2017',
            'date_to' => '31.06.2017',
            'limit' => 4000,
            'users_id' => 1,

        ],
        [
            'id' => 2,
            'title' => 'Lipiec 2017',
            'date_from' => '01.07.2017',
            'date_to' => '31.07.2017',
            'limit' => 3500,
            'users_id' => 1,

        ],
    ];

    /**
     * Find all budgets
     *
     * @return array Result
     */
    public function findAll()
    {
        return $this->budgets;
    }

    /**
     * Find budgets by its id.
     *
     * @param integer $id budgets id
     *
     * @return array Result
     */
    public function findOneById($id)
    {
        $budget = [];

        if (isset($this->budgets[$id]) && count($this->budgets[$id])) {
            $budget = $this->budgets[$id];
        }

        return $budget;
    }
}



