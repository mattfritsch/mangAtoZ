<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository
{
    public function getOrdersWithChapters(User $user)
    {
        $queryBuilder = $this->createQueryBuilder('orders');
        $queryBuilder->join('orders.order_product', 'op');
        $queryBuilder->addSelect('op');
        $queryBuilder->andWhere('orders.user = :user');
        $queryBuilder->setParameter('user', $user);
        $queryBuilder->addOrderBy('orders.orderDateTime', "ASC");
        return $queryBuilder->getQuery()->getResult();
    }
}
