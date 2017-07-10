<?php
/**
 * Roles
 *
 *
 * @author Kinga
 * @copyright (c) 2017
 */
namespace Model\Roles\Arr;

/**
 * Class Roles
 */
class Roles
{
    protected $roles = [
        [
            'id' => 1,
            'title' => 'admin',
        ],
        [
            'id' => 2,
            'title' => 'user',
        ],
        [
            'id' => 3,
            'title' => 'guest',
        ],
    ];

    /**
     * Find all roles
     *
     * @return array Result
     */
    public function findAll()
    {
        return $this->roles;
    }

    /**
     * Find roles by its id.
     *
     * @param integer $id roles id
     *
     * @return array Result
     */
    public function findOneById($id)
    {
        $role = [];

        if (isset($this->roles[$id]) && count($this->roles[$id])) {
            $role = $this->roles[$id];
        }

        return $role;
    }
}



