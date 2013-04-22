<?php

foreach (array(
    'googlecart',
    'googleitem',
    'googlelog',
    'googlemerchantcalculations',
    'googlenotification',
    'googlenotificationhistory',
    'googlepoll',
    'googlerequest',
    'googleresponse',
    'googleresult',
    'googleshipping',
    'googlesubscription',
    'googletax',
    'htmlSignatureGen',
    ) as $file) {
    require_once __DIR__ . '/' . $file . '.php';
}
