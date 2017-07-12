<?php
/**
 * Categorie repository.
 */
namespace Repository;

use Doctrine\DBAL\Connection;

/**
 * Class CategorieRepository.
 */
class CategorieRepository
{
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
}