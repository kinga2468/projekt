<?php
/**
 * Categorie repository.
 */
namespace Repository;

use Doctrine\DBAL\Connection;
use Utils\Paginator;

/**
 * Class CategorieRepository.
 */
class CategorieRepository
{
    /**
     * Number of items per page.
     *
     * const int NUM_ITEMS
     */
    const NUM_ITEMS = 10;
    /**
     * Doctrine DBAL connection.
     */
    protected $db;

    /**
     * CategorieRepository constructor.
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Fetch all records.
     */
    public function findAll()
    {
        $queryBuilder = $this->queryAll();

        return $queryBuilder->execute()->fetchAll();
    }

    /**
     * Find one record.
     */
    public function findOneById($id)
    {
        $queryBuilder = $this->queryAll();
        $queryBuilder->where('c.id = :id')
            ->setParameter(':id', $id, \PDO::PARAM_INT);
        $result = $queryBuilder->execute()->fetch();

        return !$result ? [] : $result;
    }

    /**
    * Query all records.
    */
    protected function queryAll()
    {
        $queryBuilder = $this->db->createQueryBuilder();

        return $queryBuilder->select('c.id', 'c.name', 'c.icon')
            ->from('categorie', 'c');
    }

    /**
     * Get records paginated.
     */
    public function findAllPaginated($page = 1)
    {
        $countQueryBuilder = $this->queryAll()
            ->select('COUNT(DISTINCT c.id) AS total_results')
            ->setMaxResults(1);

        $paginator = new Paginator($this->queryAll(), $countQueryBuilder);
        $paginator->setCurrentPage($page);
        $paginator->setMaxPerPage(self::NUM_ITEMS);

        return $paginator->getCurrentPageResults();
    }


    /**
     * Count all pages.
     */
    protected function countAllPages()
    {
        $pagesNumber = 1;

        $queryBuilder = $this->queryAll();
        $queryBuilder->select('COUNT(DISTINCT c.id) AS total_results')
            ->setMaxResults(1);

        $result = $queryBuilder->execute()->fetch();

        if ($result) {
            $pagesNumber =  ceil($result['total_results'] / self::NUM_ITEMS);
        } else {
            $pagesNumber = 1;
        }

        return $pagesNumber;
    }

    /**
     * Save record.
     */
    public function save($categorie)
    {
        if (isset($categorie['id']) && ctype_digit((string) $categorie['id'])) {
            // update record
            $id = $categorie['id'];
            unset($categorie['id']);

            return $this->db->update('categorie', $categorie, ['id' => $id]);
        } else {
            // add new record
            return $this->db->insert('categorie', $categorie); // pierwsze categorie to nazwa tabeli
        }
    }

    /**
     * Remove record.
     */
    public function delete($categorie)
    {
        return $this->db->delete('categorie'/*tabele categorie*/, ['id' => $categorie['id']]);
    }
}