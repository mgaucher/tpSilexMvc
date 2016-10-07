<?php
namespace App\Users\Repository;
use App\Users\Entity\User;
use Doctrine\DBAL\Connection;
/**
 * User repository.
 */
class UserRepository
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }
   /**
    * Returns a collection of users.
    *
    * @param int $limit
    *   The number of users to return.
    * @param int $offset
    *   The number of users to skip.
    * @param array $orderBy
    *   Optionally, the order by info, in the $column => $direction format.
    *
    * @return array A collection of users, keyed by user id.
    */
   public function getAll()
   {
       $queryBuilder = $this->db->createQueryBuilder();
       $queryBuilder
           ->select('u.*')
           ->from('users', 'u');
       $statement = $queryBuilder->execute();
       $usersData = $statement->fetchAll();
       foreach ($usersData as $userData) {
           $userEntityList[$userData['id']] = new User($userData['id'], $userData['nom'], $userData['prenom'],$userData['age'],$userData['adresse']);
       }
       return $userEntityList;
   }
   /**
    * Returns an User object.
    *
    * @param $id
    *   The id of the user to return.
    *
    * @return array A collection of users, keyed by user id.
    */
   public function getById($id)
   {
       $queryBuilder = $this->db->createQueryBuilder();
       $queryBuilder
           ->select('u.*')
           ->from('users', 'u')
           ->where('id = ?')
           ->setParameter(0, $id);
       $statement = $queryBuilder->execute();
       $userData = $statement->fetchAll();
       return new User($userData[0]['id'], $userData[0]['nom'], $userData[0]['prenom'],$userData[0]['age'],$userData[0]['adresse']);
   }
    public function delete($id)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
          ->delete('users')
          ->where('id = :id')
          ->setParameter(':id', $id);
        $statement = $queryBuilder->execute();
    }
    public function update($parameters)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
          ->update('users')
          ->where('id = :id')
          ->setParameter(':id', $parameters['id']);
        if ($parameters['nom']) {
            $queryBuilder
              ->set('nom', ':nom')
              ->setParameter(':nom', $parameters['nom']);
        }
        if ($parameters['prenom']) {
            $queryBuilder
            ->set('prenom', ':prenom')
            ->setParameter(':prenom', $parameters['prenom']);
        }
        if ($parameters['age']) {
            $queryBuilder
              ->set('age', ':age')
              ->setParameter(':age', $parameters['age']);
        }
        if ($parameters['adresse']) {
            $queryBuilder
              ->set('adresse', ':adresse')
              ->setParameter(':adresse', $parameters['adresse']);
        }
        $statement = $queryBuilder->execute();
    }
    public function insert($parameters)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
          ->insert('users')
          ->values(
              array(
                
                'nom' => ':nom',
                'prenom' => ':prenom',
                'age' => ':age',
                'adresse' => ':adresse',
              )
          )
         
          ->setParameter(':nom', $parameters['nom'])
          ->setParameter(':prenom', $parameters['prenom'])
          ->setParameter(':age', $parameters['age'])
          ->setParameter(':adresse', $parameters['adresse']);
          
        $statement = $queryBuilder->execute();
    }
}