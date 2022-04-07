<?php

namespace App\Repository;

use App\Entity\Animaux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Animaux|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animaux|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animaux[]    findAll()
 * @method Animaux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimauxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animaux::class);
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



}
