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

        $this->autoloadGoogleLibrary();
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
        else {
            $originalNewOrder = null;
        }

        $this->get('amy_google_checkout')->newNotification(
            $notification->getXmlAsSimpleXMLElement(),
            $originalNewOrder ? $originalNewOrder->getXmlAsSimpleXMLElement() : null
        );

        // A '200 OK' response needs to be sent.
        return new Response(null, 200);
    }

    private function autoloadGoogleLibrary()
    {
        // @todo - move to somewhere more appropriate.
        require_once __DIR__ . '/../lib/checkout/library/autoload.php';
    }

    private function getXmlBySerialNumber($serial)
    {
        // Request the notification's XML data.
        $notification = new \GoogleNotificationHistoryRequest(
            $this->getSetting('merchant_id'),
            $this->getSetting('merchant_key'),
            $this->getSetting('server_type')
        );
        $notificationResponse = $notification->SendNotificationHistoryRequest(
            $serial,
            null,
            array(),
            array(),
            null,
            null,
            $this->getSetting('ssl_certificate_path')
        );
        if (!is_array($notificationResponse) || $notificationResponse[0] != 200) {
            throw new Exception('Serial ' . $serial . ' has unexpected history response: ' . print_r($notificationResponse, true));
        }
        return $notificationResponse[1];
    }

    private function getSetting($setting)
    {
        return $this->container->getParameter('amy_google_checkout.' . $setting);
    }
}
