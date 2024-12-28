<?php

namespace App\Repository;

use App\Entity\Prospect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
* @extends ServiceEntityRepository<Prospect>
    *
    * @method Prospect|null find($id, $lockMode = null, $lockVersion = null)
    * @method Prospect|null findOneBy(array $criteria, array $orderBy = null)
    * @method Prospect[] findAll()
    * @method Prospect[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    */
    class ProspectRepository extends ServiceEntityRepository
    {
    public function __construct(ManagerRegistry $registry)
    {
    parent::__construct($registry, Prospect::class);
    }

    /**
    * Ajoute un prospect dans la base de données.
    */
    public function save(Prospect $prospect, bool $flush = false): void
    {
    $this->_em->persist($prospect);

    if ($flush) {
    $this->_em->flush();
    }
    }

    /**
    * Supprime un prospect de la base de données.
    */
    public function remove(Prospect $prospect, bool $flush = false): void
    {
    $this->_em->remove($prospect);

    if ($flush) {
    $this->_em->flush();
    }
    }

    /**
    * Exemple de requête personnalisée : récupérer les prospects clients.
    */
    public function findClients(): array
    {
    return $this->createQueryBuilder('p')
    ->andWhere('p.client = :val')
    ->setParameter('val', true)
    ->orderBy('p.nom', 'ASC')
    ->getQuery()
    ->getResult();
    }
    }