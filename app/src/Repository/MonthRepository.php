<?php
/**
 * Month repository.
 */
namespace Repository;

use Doctrine\DBAL\Connection;

/**
 * Class MonthRepository.
 */
class MonthRepository
{
    /**
     * Doctrine DBAL connection.
     */
    protected $db;

    /**
     * MonthRepository constructor.
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
        $queryBuilder->where('m.id = :id')
            ->setParameter(':id', $id, \PDO::PARAM_INT);
        $result = $queryBuilder->execute()->fetch();

        return !$result ? [] : $result;
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder Result
     */
    protected function queryAll()
    {
        $queryBuilder = $this->db->createQueryBuilder();

        return $queryBuilder->select('m.id', 'm.name', 'm.limit') //tu piszemy co selectujemy z bazy !!!!!
            ->from('month', 'm');
    }
}