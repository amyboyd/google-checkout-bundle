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
        // @todo
        return null;
    }
}
