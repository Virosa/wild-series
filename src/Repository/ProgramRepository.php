<?php

namespace App\Repository;

use App\Entity\Program;
use App\Entity\Actor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Program>
 *
 * @method Program|null find($id, $lockMode = null, $lockVersion = null)
 * @method Program|null findOneBy(array $criteria, array $orderBy = null)
 * @method Program[]    findAll()
 * @method Program[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Program::class);
    }

    public function findLikeName(string $name)
    {
        // Crée un objet QueryBuilder associé à l'entité "p" (cela représente l'entité "Program").
        $queryBuilder = $this->createQueryBuilder('p')

            ->leftJoin('p.actors', 'a') // Joindre l'entité actor avec alias 'a'.
            // Ajoute une condition à la requête : "WHERE p.title LIKE :name".
            // trouver des enregistrements où le champ "title" ressemble à la valeur fournie dans :name ou le nom de famille de acteur.
            ->andWhere('p.title LIKE :name OR a.name LIKE :name')
            // Associe la valeur de :name à la variable $name fournie en tant qu argument.
            // Notez l'utilisation de placeholders avec les deux pourcentages (%), ce qui signifie que le terme peut être n'importe où dans le titre.
            ->setParameter('name', '%' . $name . '%')
            // Ordonne les résultats par ordre alphabétique croissant sur la base du champ "title".
            ->orderBy('p.title', 'ASC')
            // Récupère la requête SQL construite jusqu'à présent.
            ->getQuery();

        // Exécute la requête et retourne les résultats.
        return $queryBuilder->getResult();
    }
    public function findRecentPrograms()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT p, s FROM App\Entity\Program p
                              INNER JOIN p.seasons s
                              WHERE s.year>2010');

        return $query->execute();
    }
//    /**
//     * @return Program[] Returns an array of Program objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Program
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
