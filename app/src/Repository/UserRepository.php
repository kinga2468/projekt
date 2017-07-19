<?php
/**
 * User repository
 */

namespace Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class UserRepository.
 *
 * @package Repository
 */
class UserRepository
{
    /**
     * Doctrine DBAL connection.
     *
     * @var \Doctrine\DBAL\Connection $db
     */
    protected $db;

    /**
     * TagRepository constructor.
     *
     * @param \Doctrine\DBAL\Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Loads user by login.
     *
     * @param string $login User login
     * @throws UsernameNotFoundException
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return array Result
     */
    public function loadUserByLogin($login)                               //ładowania użytkowania po loginie
    {
        try {                                                            //wyłapuje wyjątki, które służa do wyłapywanie błędów
            $user = $this->getUserByLogin($login);                       //znajdz użytkownika po loginie

            if (!$user || !count($user)) {                                //jeżeli nie ma takiego loginu lub tablica jest pusta
                throw new UsernameNotFoundException(                      //użyj wyjątek user name not found
                    sprintf('Username "%s" does not exist.', $login)      //który wyświetli :
                );
            }

            $roles = $this->getUserRoles($user['id']);                   //znajdz role użytkownika po jego user_id

            if (!$roles || !count($roles)) {                             //jeśli nazwy ról nie istnieją lub tablica jest pusta
                throw new UsernameNotFoundException(
                    sprintf('Username "%s" does not exist.', $login)      //wyrzuć wyjątek
                );
            }

            return [                                                       //i jak znajdziejsz to zwróć
                'login' => $user['login'],
                'password' => $user['password'],
                'roles' => $roles,
            ];
        } catch (DBALException $exception) {                                           //jeśli wystąpią błędy / wyjątki
            throw new UsernameNotFoundException(
                sprintf('Username "%s" does not exist.', $login)
            );
        } catch (UsernameNotFoundException $exception) {
            throw $exception;
        }
    }

    /**
     * Gets user data by login.
     *
     * @param string $login User login
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return array Result
     */
    public function getUserByLogin($login)                                       //uzyskaj dane użytkownika po loginie
    {
        try {
            $queryBuilder = $this->db->createQueryBuilder();                     //tworzymy zapytanie
            $queryBuilder->select('u.id', 'u.login', 'u.password')               //wyszukujemy id, login i password
                ->from('user', 'u')                                              //z tabeli user
                ->where('u.login = :login')                                      //gdzie login wpisany jest taki sam jak ten z bazy
                ->setParameter(':login', $login, \PDO::PARAM_STR);               //ustaw wartość zmiennej login na wpisany login
                                                                                 //pdo:param_str: Dla stringa: Stałe poniżej są zdefiniowane przez to rozszerzenie i będą dostępne tylko wtedy, gdy rozszerzenie zostało skompilowane w PHP lub dynamicznie ładowane w czasie wykonywania.
            return $queryBuilder->execute()->fetch();                            //wykonaj i zwróć po kolei pojedyncze wyniki
        } catch (DBALException $exception) {                                     //jeżeli coś pójdzie nie tak to użyj obsługi błędów i zwróć pustą tablice
            return [];
        }
    }

    /**
     * Gets user roles by User ID.
     *
     * @param integer $userId User ID
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return array Result
     */
    public function getUserRoles($userId)
    {
        $roles = [];

        try {
            $queryBuilder = $this->db->createQueryBuilder();                         //tworzymy zapytanie
            $queryBuilder->select('r.name')                                          //wyszukujemy nazwe roli
                ->from('user', 'u')                                                  //z tabeli user
                ->innerJoin('u', 'role', 'r', 'u.role_id = r.id')                    //połączonej z tabelą role gdzie role_id z tabeli user jest takie samo jak id z tabeli role
                ->where('u.id = :id')                                                //gdzie user id jest taki sam jak ten wprowadzony
                ->setParameter(':id', $userId, \PDO::PARAM_INT);                     //ustaw zmienną userid na wprowadzony id
            $result = $queryBuilder->execute()->fetchAll();                          //wykonaj i zwróć wszystkie wyniki

            if ($result) {                                                           //jeśli wzrócone wyniki istnieją
                $roles = array_column($result, 'name');                              //to roles = tylko kolumna name
            }

            return $roles;                                                           //wyświetl roles
        } catch (DBALException $exception) {                                         //jak wykryje błędy to zwróć roles ?
            return $roles;
        }
    }


    public function findUserId($login)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->select('id')
            ->from('user')
            ->where('login = :login')
            ->setParameter(':login', $login);
        $userId = current($queryBuilder->execute()->fetch());
        return $userId;
    }
}
