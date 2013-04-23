<?php

namespace Amy\GoogleCheckoutBundle;

use Amy\GoogleCheckoutBundle\Entity\Notification;

/**
 * Create services which extend this class, tagged as
 * 'amy_google_checkout.notification_listener'.
 *
 * Override whatever methods are required by your application.
 */
abstract class NotificationListener
{
    /**
     * Return whether or not the notification is relevant to the listener.
     * E.g. A "BookSalesListener" would return true for book orders, but false
     * for a news article subscription.
     *
     * If this returns false,
     *
     * @return boolean
     */
    abstract public function isNotificationRelevantToThisListener
        (Notification $notification, Notification $originalNewOrderNotification);

    /**
     * See https://developers.google.com/checkout/developer/Google_Checkout_XML_API_Notification_API#New_Order_Notification_Tags
     *
     * In this method, $notification == $originalNewOrderNotification.
     */
    public function onNewOrder
        (Notification $notification, Notification $originalNewOrderNotification)
    {
    }

    /**
     * See https://developers.google.com/checkout/developer/Google_Checkout_XML_API_Notification_API#order_state_change_notification
     */
    public function onStateChange
        (Notification $notification, Notification $originalNewOrderNotification)
    {
    }

    /**
     * See https://developers.google.com/checkout/developer/Google_Checkout_XML_API_Notification_API#Risk_Information_Notification_Tags
     */
    public function onRiskInformationReceived
        (Notification $notification, Notification $originalNewOrderNotification)
    {
    }

    /**
     * See https://developers.google.com/checkout/developer/Google_Checkout_XML_API_Notification_API#authorization_amount_notification
     */
    public function onAuthorization
        (Notification $notification, Notification $originalNewOrderNotification)
    {
    }

    /**
     * See https://developers.google.com/checkout/developer/Google_Checkout_XML_API_Notification_API#charge_amount_notification
     */
    public function onSuccessfulCharge
        (Notification $notification, Notification $originalNewOrderNotification)
    {
    }

    /**
     * See https://developers.google.com/checkout/developer/Google_Checkout_XML_API_Notification_API#refund_amount_notification
     */
    public function onRefund
        (Notification $notification, Notification $originalNewOrderNotification)
    {
    }

    /**
     * See https://developers.google.com/checkout/developer/Google_Checkout_XML_API_Notification_API#chargeback_amount_notification
     */
    public function onChargeback
        (Notification $notification, Notification $originalNewOrderNotification)
    {
    }

    /**
     * Subscriptions are currently an undocumented beta feature.
     *
     * The XML looks like (as of 23 April 2013):
     * <cancelled-subscription-notification xmlns="http://checkout.google.com/schema/2" serial-number="123456789-00013-9">
     *   <item-ids>
     *     <item-id>
     *       <merchant-item-id>subscription</merchant-item-id>
     *     </item-id>
     *   </item-ids>
     *   <reason>Customer request to cancel</reason>
     *   <google-order-number>123456789</google-order-number>
     *   <timestamp>2013-03-30T23:14:42.166Z</timestamp>
     * </cancelled-subscription-notification>
     */
    public function onCancelledSubscription
        (Notification $notification, Notification $originalNewOrderNotification)
    {
    }
}
