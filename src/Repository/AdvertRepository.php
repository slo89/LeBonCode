<?php

namespace App\Repository;

use App\Entity\Advert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * AdvertRepository
 */
class AdvertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advert::class);
    }

    public function add(Advert $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Advert $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function disable(Advert $entity): void
    {
        $entity->setOnLine(false);
        $this->_em->flush();
    }

    public function update(Advert $advert, Advert $updatedAdvert): void
    {

        $advert->setDescription($updatedAdvert->getDescription()??$advert->getDescription());
        $advert->setSalePrice($updatedAdvert->getSalePrice()??$advert->getSalePrice());
        $advert->setPostalCode($updatedAdvert->getPostalCode()??$advert->getPostalCode());
        $advert->setCityOfSale($updatedAdvert->getCityOfSale()??$advert->getCityOfSale());

        $this->_em->flush();
    }

    public function search(?string $title, ?int $priceMin, ?int $priceMax): array
    {
        $qb = $this->createQueryBuilder('a');

        if ($title) {
            $qb->andWhere('a.title LIKE :title')
                ->setParameter('title', "%$title%");
        }

        if ($priceMin) {
            $qb->andWhere('a.salePrice >= :priceMin')
                ->setParameter('priceMin', $priceMin);
        }

        if ($priceMax) {
            $qb->andWhere('a.salePrice <= :priceMax')
                ->setParameter('priceMax', $priceMax);
        }

        return $qb->getQuery()->getResult();
    }

}
