<?php

namespace App\Repository;

use App\Entity\Categ;
use Doctrine\ORM\EntityRepository;

class CategRepository extends EntityRepository
{
    public function getSelectedCategories($id)
    {
        $queryBuilder = $this->createQueryBuilder('categ');
        $queryBuilder->andWhere('categ.categId = :categId');
        $queryBuilder->setParameter('categId', $id);
        return $queryBuilder->getQuery()->getResult();
    }
}
