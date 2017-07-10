<?php
/**
 * Datas
 *
 *
 * @author Kinga
 * @copyright (c) 2017
 */
namespace Model\Datas\Arr;

/**
 * Class Datas
 */
class Datas
{
    protected $datas = [
        [
            'email' => 'kinga.bozecka@op.pl',
            'name' => 'Kinga',
            'surname' => 'Bożęcka',
            'gender' => 'female',
            'users_id' => 1,
        ],
    ];

    /**
     * Find all datas
     *
     * @return array Result
     */
    public function findAll()
    {
        return $this->datas;
    }

    /**
     * Find datas by its id.
     *
     * @param integer $id datas id
     *
     * @return array Result
     */
    public function findOneById($id)
    {
        $data = [];

        if (isset($this->datas[$id]) && count($this->datas[$id])) {
            $data = $this->datas[$id];
        }

        return $data;
    }
}



