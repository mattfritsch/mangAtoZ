<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

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
        $queryBuilder = $this->createQueryBuilder('product');
        foreach($filters as $name => $value){
            if($name === 'search'){
                if($value !== NULL){
                    $queryBuilder->andWhere('product.productName LIKE :searchTerm');
                    $queryBuilder->setParameter('searchTerm', '%'.$value.'%');
                }
            }

            if($name === 'categories'){
                if($value !== ''){
                    $queryBuilder->leftJoin('product.categories', 'pc');
                    $queryBuilder->addSelect('pc');
                    foreach($value as $category){
                        $queryBuilder->andWhere('pc.categId = :categValue');
                        $queryBuilder->setParameter('categValue', $category);
                    }
                }
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
                if($value !== 'nocensure'){
                    $queryBuilder->andWhere('product.ageRank = 0');
                }
            }
        }
        $queryBuilder->andWhere('product.notAvailable = 0');
        return $queryBuilder->getQuery();
    }
}
