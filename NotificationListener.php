<?php

namespace Amy\GoogleCheckoutBundle;

use Amy\GoogleCheckoutBundle\Entity\Notification;

// @todo - add documentation.
abstract class NotificationListener
{
    abstract public function isNotificationRelevantToThisListener
        (Notification $notification, Notification $originalNewOrderNotification);

    /**
     * In this case, $notification == $originalNewOrderNotification.
     */
    public function onNewOrder
        (Notification $notification, Notification $originalNewOrderNotification)
    {
    }

    public function onStateChange
        (Notification $notification, Notification $originalNewOrderNotification)
    {
    }

    public function onRiskInformationReceived
        (Notification $notification, Notification $originalNewOrderNotification)
    {
    }

    public function onAuthorization
        (Notification $notification, Notification $originalNewOrderNotification)
    {
    }

    public function onSuccessfulCharge
        (Notification $notification, Notification $originalNewOrderNotification)
    {
    }

    public function onRefund
        (Notification $notification, Notification $originalNewOrderNotification)
    {
    }

    public function onChargeback
        (Notification $notification, Notification $originalNewOrderNotification)
    {
    }

    public function onCancelledSubscription
        (Notification $notification, Notification $originalNewOrderNotification)
    {
    }
}
