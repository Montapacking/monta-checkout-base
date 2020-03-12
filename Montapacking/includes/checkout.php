<?php
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT']."/");

require(BASE_PATH . "Montapacking/includes/config.php");
require(BASE_PATH . "Montapacking/includes/autoload.php");

$template = new Montapacking_TinyTemplate();
$template->setFile("Checkout/checkout.phtml");

$template->addStyleSheet("styles.checkbox.css");
$template->addStyleSheet("styles.css");
$template->addStyleSheet("cssmenu.css");
$template->addStyleSheet("styles.custom.css");


$template->addJavascript("jquery-3.4.1.min.js");
$template->addJavascript("https://maps.google.com/maps/api/js?key=".GOOGLE_API_KEY);

$template->addJavascript("handlebars.js");
$template->addJavascript("storelocator.js");
$template->addJavascript("cssmenu.js");
$template->addJavascript("checkout.js");

$address = array();
if (isset($_REQUEST['addressdata'])) {
    /*
    $address['street'] = "Ocker Repelaerstraat";
    $address['housenumber'] = "16";
    $address['housenumberaddition'] = "";
    $address['postalcode'] = "2973AV";
    $address['city'] = "Molenaarsgraaf";
    $address['state'] = "Zuid-Holland";
    $address['country'] = "Nederland";
    */

    //var_dump($address); exit;

    $address = $_REQUEST['addressdata'];
    $address['country'] = "Nederland";
}

$obj = new Montapacking_Shipping_Shipping(API_SHOP, API_USER, API_PASSWORD, API_LANGUAGE, GOOGLE_API_KEY, $address, false);
$obj->setDebug(false);

//$obj->setOrder("100", "121");

$template->assign("oItem", $obj);

echo $template->process();
