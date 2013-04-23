<?php

namespace Amy\GoogleCheckoutBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Amy\GoogleCheckoutBundle\Entity\Notification;
use Amy\GoogleCheckoutBundle\Exception;

class DefaultController extends Controller
{
    public function ipnAction(Request $request)
    {
        $serial = $request->get('serial-number');
        $xml = $this->getXmlBySerialNumber($serial);

        $notification = new Notification($xml);
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($notification);
        $em->flush();

        if ($notification->getType() != Notification::TYPE_NEW_ORDER) {
            $originalNewOrder = $this->getDoctrine()
                ->getRepository('AmyGoogleCheckoutBundle:Notification')
                ->findOriginalNewOrderNotification($notification);
        }

        $this->get('amy_google_checkout')->newNotification(
            $notification,
            isset($originalNewOrder) ? $originalNewOrder : null
        );

        // A '200 OK' response needs to be sent.
        return new Response(null, 200);
    }

    private function getXmlBySerialNumber($serial)
    {
        $this->get('amy_google_checkout')->autoloadGoogleClasses();

        // Request the notification's XML data.
        $notification = new \GoogleNotificationHistoryRequest(
            $this->get('amy_google_checkout')->getMerchantID(),
            $this->get('amy_google_checkout')->getMerchantKey(),
            $this->get('amy_google_checkout')->getServerType()
        );
        $notificationResponse = $notification->SendNotificationHistoryRequest(
            $serial,
            null,
            array(),
            array(),
            null,
            null,
            $this->get('amy_google_checkout')->getSslCertificatePath()
        );
        if (!is_array($notificationResponse) || $notificationResponse[0] != 200) {
            throw new Exception('Serial ' . $serial . ' has unexpected history response: ' . print_r($notificationResponse, true));
        }
        return $notificationResponse[1];
    }
}
