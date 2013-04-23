<?php

namespace Amy\GoogleCheckoutBundle\Entity;

use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository
{
    /**
     * @return Notification
     */
    public function findOriginalNewOrderNotification(Notification $notification)
    {
        return $this->createQueryBuilder('n')
            ->where('n.orderNumber = :order_number')
            ->setParameter('order_number', $notification->getOrderNumber())
            ->andWhere('n.type = :new_order')
            ->setParameter('new_order', Notification::TYPE_NEW_ORDER)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
