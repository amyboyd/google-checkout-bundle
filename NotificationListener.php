<?php

namespace Amy\GoogleCheckoutBundle;

use Amy\GoogleCheckoutBundle\Entity\Notification;

// @todo - add documentation.
abstract class NotificationListener
{
    abstract public function isNotificationRelevantToThisListener
        (Notification $notification, Notification $originalNewOrderNotification = null);

    public function onNewOrder
        (Notification $notification, Notification $originalNewOrderNotification = null)
    {
    }

    public function onStateChange
        (Notification $notification, Notification $originalNewOrderNotification = null)
    {
    }

    public function onRiskInformationReceived
        (Notification $notification, Notification $originalNewOrderNotification = null)
    {
    }

    public function onAuthorization
        (Notification $notification, Notification $originalNewOrderNotification = null)
    {
    }

    public function onSuccessfulCharge
        (Notification $notification, Notification $originalNewOrderNotification = null)
    {
    }

    public function onRefund
        (Notification $notification, Notification $originalNewOrderNotification = null)
    {
    }

    public function onChargeback
        (Notification $notification, Notification $originalNewOrderNotification = null)
    {
    }

    public function onCancelledSubscription
        (Notification $notification, Notification $originalNewOrderNotification = null)
    {
    }
}
