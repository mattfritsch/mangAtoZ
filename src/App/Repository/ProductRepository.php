<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function search($term)
    {
        return $this->createQueryBuilder('product')
            ->andWhere('product.productName LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$term.'%')
            ->getQuery()
            ->execute();
    }

    public function getFilteredProducts(array $filters=[]){
        var_dump($filters);
        $queryBuilder = $this->createQueryBuilder('product');
        foreach($filters as $name => $value){
            if($name === 'search'){
                $queryBuilder->andWhere('product.productName LIKE :searchTerm');
                $queryBuilder->setParameter('searchTerm', '%'.$value.'%');
            }

            if($name === 'order'){
                if($value === 'alphabetical')
                    $queryBuilder->addOrderBy('product.productName', 'ASC');
                else if($value === 'popularity')
                    $queryBuilder->addOrderBy('product.averageRating', 'DESC');
            }
            if($name === 'status'){
                if($value === 'inprogress'){
                    $queryBuilder->andWhere('product.status = 0');
                }
                else if ($value === 'finished'){
                    $queryBuilder->andWhere('product.status = 1');
                }
            }
            if($name === 'censure'){
                if($value === 'nocensure'){
                    $queryBuilder->andWhere('product.ageRank = 0');
                }
                else{
                    $queryBuilder->andWhere('product.ageRank = 1');
                }
            }
        }
        return $queryBuilder->getQuery()->getResult();
    }
}
