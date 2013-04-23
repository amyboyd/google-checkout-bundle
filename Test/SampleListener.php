<?php

namespace Amy\GoogleCheckoutBundle\Test;

use Amy\GoogleCheckoutBundle\Entity\Notification;
use Amy\GoogleCheckoutBundle\NotificationListener;

class SampleListener extends NotificationListener
{
    public function isNotificationRelevantToThisListener(Notification $notification, Notification $originalNewOrderNotification = null)
    {
        return true;
    }
}
