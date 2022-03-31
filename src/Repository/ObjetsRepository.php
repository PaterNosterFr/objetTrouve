<?php

namespace App\Repository;

use App\Entity\Objets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Objets|null find($id, $lockMode = null, $lockVersion = null)
 * @method Objets|null findOneBy(array $criteria, array $orderBy = null)
 * @method Objets[]    findAll()
 * @method Objets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObjetsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Objets::class);
    }

	public function count ( array $criteria )
	{
		return parent ::count ( $criteria );
	}

	// fonction de recherche personnalisée à appeler ensuite dans le controller de l'entité liée
	public function trouverUneAnnonce()
	{
		//QueryBuilder
		$queryBuilder = $this -> createQueryBuilder ('o');

		// si besoin de condition de filtre :
		//$queryBuilder->andWhere ('o.');

		//organisation des résultats par date descendante :
		$queryBuilder -> addOrderBy ('o.date', 'DESC');

		$query = $queryBuilder->getQuery ();
		$query->setMaxResults (30);

		$results = $query -> getResult ();
		return $results;
	}



    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Objets $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Objets $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Objets[] Returns an array of Objets objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Objets
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
