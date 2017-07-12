<?php
/**
 * Operation repository.
 */
namespace Repository;

use Doctrine\DBAL\Connection;

/**
 * Class OperationRepository.
 */
class OperationRepository
{
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

        return $queryBuilder->select('o.id', 'o.name', 'o.value')
            ->from('operation', 'o');
    }
}