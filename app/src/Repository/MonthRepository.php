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
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder Result
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
    public function save($month)
    {
        if (isset($month['id']) && ctype_digit((string) $month['id'])) {
            // update record
            $id = $month['id'];
            unset($month['id']);

            return $this->db->update('month', $month, ['id' => $id]);
        } else {
            // add new record
            return $this->db->insert('month', $month); // pierwsze categorie to nazwa tabeli
        }
    }

    /**
     * znajdź te miesiące - nazwy limity, które id się zgadza

    public function findMonthByUserID($id)
    {
        $queryBuilder = $this->db->createQueryBuilder();
            $queryBuilder->select('m.name')
            ->from('user', 'u')
            ->innerJoin('u', 'month', 'm', 'm.user_id = u.id')
            ->where('u.id = :id')
            ->setParameter(':id', $id, \PDO::PARAM_INT);

            return $queryBuilder->execute()->fetch();
    }*/

    /*
    public function findMonthByUser($userLogin)
    {
        $userId = $this->findUserId($userLogin);
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->select('m.name')
            ->from('user', 'u')
            ->join('u', 'month', 'm', 'm.user_id = u.id')
            ->where('u.id = :id')
            ->setParameter(':id', $userId, \PDO::PARAM_INT)
            ->orderBy('date_from');
        $result = $queryBuilder->execute()->fetch();
        return $result;
    }

    protected function findUserId($login)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->select('id')
            ->from('user')
            ->where('login = :login')
            ->setParameter(':login', $login);
        $userId = current($queryBuilder->execute()->fetch());
        return $userId;
    }*/

    public function getUserMonth($userId)
    {
        $month = [];

        try {
            $queryBuilder = $this->db->createQueryBuilder();
            $queryBuilder->select('m.name')
            ->from('month', 'm')
            ->innerJoin('m', 'user', 'u', 'm.user_id = u.id')
            ->where('u.id = :id')
            ->setParameter(':id', $userId, \PDO::PARAM_INT);
            $result = $queryBuilder->execute()->fetchAll();

            if ($result) {
                $month = array_column($result, 'name');
            }

            return $month;
        } catch (DBALException $exception) {
            return $month;
        }
    }
}