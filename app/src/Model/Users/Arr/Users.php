<?php
/**
 * Users
 *
 *
 * @author Kinga
 * @copyright (c) 2017
 */
namespace Model\Users\Arr;

/**
 * Class Users
 */
class Users
{
    protected $users = [
        [
            'id' => 1,
            'password' => 'abc',
            'login' => 'ABC',
            'roles_id' => 1,
        ],
        [
            'id' => 2,
            'password' => 'def',
            'login' => 'DEF',
            'roles_id' => 2,
        ],
        [
            'id' => 3,
            'password' => 'ghi',
            'login' => 'GHI',
            'roles_id' => 3,
        ],
    ];

    /**
     * Find all users
     *
     * @return array Result
     */
    public function findAll()
    {
        return $this->users;
    }

    /**
     * Find users by its id.
     *
     * @param integer $id users id
     *
     * @return array Result
     */
    public function findOneById($id)
    {
        $user = [];

        if (isset($this->users[$id]) && count($this->users[$id])) {
            $user = $this->users[$id];
        }

        return $user;
    }
}



