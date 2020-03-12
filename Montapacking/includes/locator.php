<?php
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT']."/");

require(BASE_PATH . "Montapacking/includes/config.php");
require(BASE_PATH . "Montapacking/includes/autoload.php");

$template = new Montapacking_TinyTemplate();
$template->setFile("Storelocator/locator.phtml");

$template->addStyleSheet("locator.css");

$template->addJavascript("jquery-3.4.1.min.js");
$template->addJavascript("handlebars.js");
$template->addJavascript("storelocator.js");

echo $template->process();
