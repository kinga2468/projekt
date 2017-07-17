<?php
/**
 * Month repository.
 */
namespace Repository;

use Doctrine\DBAL\Connection;
use Utils\Paginator;
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
    const NUM_ITEMS = 10;                          //ilość wyników wyświetlanych na stronie
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
    public function findAll()                                    //funckja find all
    {
        $queryBuilder = $this->queryAll();                       //weź wszystko

        return $queryBuilder->execute()->fetchAll();             //i wyświetl wszystko
    }

    /**
     * Find one record.
     */
    public function findOneById($id)                               //funkcja find one by id
    {
        $queryBuilder = $this->queryAll();                         //pobierz wszystko z było z query all
        $queryBuilder->where('m.id = :id')                         //weź te gdzie id_month jest równe id
            ->setParameter(':id', $id, \PDO::PARAM_INT);
        $result = $queryBuilder->execute()->fetch();               //wyświetl to co zostało wybrane

        return !$result ? [] : $result;                            //jeśli nie istnieje result to wyświetl pustą tablice a jak istnieje to result
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder Result
     */
    protected function queryAll()
    {
        $queryBuilder = $this->db->createQueryBuilder();

        return $queryBuilder->select('m.id', 'm.name', 'm.date_from', 'm.date_to', 'm.upper_limit') //tu piszemy co selectujemy z bazy !!!!!
            ->from('month', 'm');                //i jakim skrótem będzie to opatrzone
    }

    /**
     * Get records paginated.
     */
    public function findAllPaginated($page = 1)                        //robi pagniacje
    {
        $countQueryBuilder = $this->queryAll()                              //wybiera rzeczy z bazy co przeszły przez queryall
            ->select('COUNT(DISTINCT m.id) AS total_results')               //liczy unikalne id month jako total_results
            ->setMaxResults(1);                                             //ustawia max rezultatów na 1?

        $paginator = new Paginator($this->queryAll(), $countQueryBuilder);   //
        $paginator->setCurrentPage($page);                                   //zapamiętuje obecną stronę
        $paginator->setMaxPerPage(self::NUM_ITEMS);                          //ustawia stałą ilość wyników na stronie

        return $paginator->getCurrentPageResults();
    }

    /**
     * Count all pages.
     *
     * @return int Result
     */
    protected function countAllPages()                                         //liczy ilość stron
    {
        $pagesNumber = 1;

        $queryBuilder = $this->queryAll();
        $queryBuilder->select('COUNT(DISTINCT m.id) AS total_results')
            ->setMaxResults(1);

        $result = $queryBuilder->execute()->fetch();

        if ($result) {
            $pagesNumber =  ceil($result['total_results'] / self::NUM_ITEMS);         //ilość stron =  ilość wyników podzielić na max ilość na stronie
        } else {
            $pagesNumber = 1;                                                        //inaczej ilość stron to jeden
        }

        return $pagesNumber;
    }

    /**
     * Save record.
     */
    public function save($month)                                              //zapisywanie do bazy
    {
        if (isset($month['id']) && ctype_digit((string) $month['id'])) {      //jeżeli zmienna istnieje oraz wszystko jest stringami
            // update record
            $id = $month['id'];                                                //zmienna id przyjmie wartość id utworzonego rekordu
            unset($month['id']);                                               //zwolnimy wartość id utorzonego rekordu

            return $this->db->update('month', $month, ['id' => $id]);            //zaaktualizujemy tabele month dodając nowe dane ze zmiennej month
        } else {
            // add new record
            return $this->db->insert('month', $month);                       //a jak nie istnieje to dodajemy nowy rekord
        }
    }

    /**
     * znajdź te miesiące - nazwy limity, które id się zgadza
     */
    public function findAllById($id)
    {
        $queryBuilder = $this->db->createQueryBuilder();
            $queryBuilder->select('m.name')
            ->from('user', 'u')
            ->innerJoin('u', 'month', 'm', 'm.user_id = u.id')
            ->where('u.id = :id')
            ->setParameter(':id', $id, \PDO::PARAM_INT);

            return $queryBuilder->execute()->fetch();
    }

}