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
    public function findAll($tab)
    {
        $queryBuilder = $this->queryAll($tab);

        return $queryBuilder->execute()->fetchAll();
    }

    /**
     * Find one record.
     */
    public function findOneById($id, $tab)
    {
        $queryBuilder = $this->queryAll($tab);
        $queryBuilder->where('id = :id')
            ->setParameter(':id', $id, \PDO::PARAM_INT);
        $result = $queryBuilder->execute()->fetch();

        return !$result ? [] : $result;
    }

    /**
     * Query all records.
     */
    protected function queryAll($tab)
    {
        $queryBuilder = $this->db->createQueryBuilder();

        return $queryBuilder->select('*')
            ->from($tab);
    }

    /**
     * Get records paginated.
     */
    public function findAllPaginated($page, $tab)
    {
        $countQueryBuilderModifier = function ($queryBuilder) {
            $queryBuilder->select('COUNT(DISTINCT id) AS total_results')
                ->setMaxResults(1);
        };

        $queryBuilder = $this->queryAll($tab);

        $adapter = new DoctrineDbalAdapter($queryBuilder, $countQueryBuilderModifier);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(self::NUM_ITEMS);
        $pagerfanta->setCurrentPage($page);
        return $pagerfanta;
    }

    public function getMonth($month_id)
    {
        $operation = [];

        try {
            $queryBuilder = $this->db->createQueryBuilder();
            $queryBuilder->select('o.name', 'o.id')
                ->from('operation', 'o')
                ->innerJoin('o', 'month', 'm', 'o.month_id = m.id')
                ->where('m.id = :id')
                ->setParameter(':id', $month_id, \PDO::PARAM_INT);
            $result = $queryBuilder->execute()->fetchAll();

            if ($result) {
                $operation = array_column($result, 'name');
            }

            return $operation;
        } catch (DBALException $exception) {
            return $operation;
        }
    }

    public function getCategory($category_id)
    {
        $operation = [];

        try {
            $queryBuilder = $this->db->createQueryBuilder();
            $queryBuilder->select('o.name','o.id')
                ->from('operation', 'o')
                ->innerJoin('o', 'categorie', 'c', 'o.categorie_id = c.id')
                ->where('c.id = :id')
                ->setParameter(':id', $category_id, \PDO::PARAM_INT);
            $result = $queryBuilder->execute()->fetchAll();

            if ($result) {
                $operation = array_column($result, 'name');
            }

            return $operation;
        } catch (DBALException $exception) {
            return $operation;
        }
    }

    public function loadOperationById($id)
    {
        try {
            $month = $this->getMonth($id);

            $category = $this->getCategory($month['id']);

            return [
                'month' => $month['name'],
                'category' => $category['name'],
            ];
        } catch (DBALException $exception) {
            throw (
            sprintf('Operation "%s" does not exist.', $id)
            );
        }
    }
}
