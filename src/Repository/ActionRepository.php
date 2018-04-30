<?php

namespace App\Repository;

use App\Entity\Action;
use App\Entity\Event;
use \Doctrine\ORM\Query\Expr\Join;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Action|null find($id, $lockMode = null, $lockVersion = null)
 * @method Action|null findOneBy(array $criteria, array $orderBy = null)
 * @method Action[]    findAll()
 * @method Action[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Action::class);
    }

    public function findByUserId($value)
    {
		$conn = $this->getEntityManager()->getConnection();
		$stmt = $conn->prepare('SELECT * 
			FROM event e 
			LEFT JOIN action a 
			ON e.id = a.event_id 
			Where user_id IS NULL 
			OR a.user_id = '.$value.'
			AND a.date >= (NOW() - INTERVAL 1 MONTH)
			ORDER BY a.date ASC;');
		$stmt->execute();

		return $stmt->fetchAll();
    }

//    /**
//     * @return Action[] Returns an array of Action objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Action
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
