<?php
/**
 * Categories
 *
 *
 * @author Kinga
 * @copyright (c) 2017
 */
namespace Model\Categories\Arr;

/**
 * Class Categories
 */
class Categories
{
    protected $categories = [
        [
            'id' => 1,
            'icon' =>'fa fa-money',
            'title' => 'Oszczędności',
        ],
        [
            'id' => 2,
            'icon' =>'fa fa-shopping-cart',
            'title' => 'Zakupy',
        ],
        [
            'id' => 3,
            'icon' =>'fa fa-car',
            'title' => 'Paliwo',
        ],
        [
            'id' => 4,
            'icon' =>'fa fa-credit-card',
            'title' => 'Rachunki',
        ],
        [
            'id' => 5,
            'icon' =>'fa fa-gamepad',
            'title' => 'Rozrywka',
        ],
    ];

    /**
     * Find all categories
     *
     * @return array Result
     */
    public function findAll()
    {
        return $this->categories;
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
        $categorie = [];

        if (isset($this->categories[$id]) && count($this->categories[$id])) {
            $categorie = $this->categories[$id];
        }

        return $categorie;
    }
}



