<?php
/**
 * Month repository.
 */
namespace Repository;

use Doctrine\DBAL\Connection;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineDbalAdapter;
use Doctrine\DBAL\DBALException;
/**
 * Class MonthRepository.
 */
class MonthRepository
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

        return $queryBuilder->select('*')
            ->from('month', 'm');
    }

    /**
     * Get records paginated.
     */
    public function findAllPaginated($page, $tab, $date)
    {
        $countQueryBuilderModifier = function ($queryBuilder) {
            $queryBuilder->select('COUNT(DISTINCT id) AS total_results')
                ->setMaxResults(1);
        };

        $queryBuilder = $this->queryAll($tab)
            ->orderBy($date, 'DESC');

        $adapter = new DoctrineDbalAdapter($queryBuilder, $countQueryBuilderModifier);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(self::NUM_ITEMS);
        $pagerfanta->setCurrentPage($page);
        return $pagerfanta;
    }

    /**
     * Save record.
     */
    public function save($month, $userLogin)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->select('u.id')
            ->from('user', 'u')
            ->where('u.login = :login')
            ->setParameter(':login', $userLogin, \PDO::PARAM_INT);
        $user_id = $queryBuilder->execute()->fetchAll();

        if (isset($month['id']) && ctype_digit((string) $month['id']) && isset($user_id)) {
            // update record
            $id = $month['id'];
            unset($month['id']);

            return $this->db->update('month', $month, ['id' => $id]);
        } else {
            // add new record
            return $this->db->insert('month', $month) where ; // pierwsze month to nazwa tabeli
        }
    }

    /*
     *   select this month which user is logged
     */
    public function getUserMonth($userLogin)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->select('m.name', 'm.id')
            ->from('month', 'm')
            ->innerJoin('m', 'user', 'u', 'm.user_id = u.id')
            ->where('u.login = :login')
            ->setParameter(':login', $userLogin, \PDO::PARAM_INT);
        $result = $queryBuilder->execute()->fetchAll();
        return $result;

    }
}