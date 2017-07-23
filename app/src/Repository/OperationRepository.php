<?php
/**
 * Operation repository.
 */
namespace Repository;

use Doctrine\DBAL\Connection;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineDbalAdapter;
use Pagerfanta\Adapter\ArrayAdapter;
use Doctrine\DBAL\DBALException;

/**
 * Class OperationRepository.
 */
class OperationRepository
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
     * OperationRepository constructor.
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
        $queryBuilder->where('o.id = :id')
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

        return $queryBuilder->select('*')
            ->from('operation', 'o');
    }

    /**
     * Get records paginated.
     */
    public function findAllPaginated($page)
    {
        $countQueryBuilderModifier = function ($queryBuilder) {
            $queryBuilder->select('COUNT(DISTINCT id) AS total_results')
                ->setMaxResults(1);
        };

        $queryBuilder = $this->queryAll();

        $adapter = new DoctrineDbalAdapter($queryBuilder, $countQueryBuilderModifier);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(self::NUM_ITEMS);
        $pagerfanta->setCurrentPage($page);
        return $pagerfanta;
    }

    public function loadOperation($month_id, $categorie_id)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->select('o.name', 'o.id', 'o.value')
            ->from('operation', 'o')
            ->innerJoin('o', 'categorie', 'c', 'o.categorie_id = c.id')
            ->innerJoin('o', 'month', 'm', 'o.month_id = m.id')
            ->where('o.month_id = :month_id')
            ->setParameter(':month_id', $month_id, \PDO::PARAM_INT)
            ->setParameter(':categorie_id', $categorie_id, \PDO::PARAM_INT);
        $result = $queryBuilder->execute()->fetchAll();
        return $result;
    }

    public function getOperationByCategoryId($categorie_id)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->select('o.name', 'o.id', 'o.value')
            ->from('operation', 'o')
            ->innerJoin('o', 'categorie', 'c', 'o.categorie_id = c.id')
            ->where('o.categorie_id = :categorie_id')
            ->setParameter(':categorie_id', $categorie_id, \PDO::PARAM_INT);
        $result = $queryBuilder->execute()->fetchAll();
        return $result;
    }

    public function getOperationByMonthId($month_id)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->select('o.name', 'o.id', 'o.value', 'categorie_id')
            ->from('operation', 'o')
            ->innerJoin('o', 'month', 'm', 'o.month_id = m.id')
            ->where('o.month_id = :month_id')
            ->setParameter(':month_id', $month_id, \PDO::PARAM_INT);
        $result = $queryBuilder->execute()->fetchAll();
        return $result;
    }

}
