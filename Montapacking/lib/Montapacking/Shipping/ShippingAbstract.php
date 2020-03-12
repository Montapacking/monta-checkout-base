<?php

class Montapacking_Shipping_ShippingAbstract extends Montapacking_Api
{
    protected $debug = false;

    protected $origin = '';
    protected $username = '';
    protected $password = '';
    protected $googleapikey = '';
    protected $url = '';

    protected $modus = 'api';
    protected $locale = 'NL';

    protected $basic = null;
    protected $order = null;
    protected $address = null;
    protected $shippers = null;
    protected $products = null;

    protected $allowedshippers = null;
    protected $pickupoptions = null;

    protected $shippingoptions = null;

    public function __construct($origin, $username, $password, $locale = "NL", $googleapikey, $address, $bTest = false)
    {
        parent::__construct();

        $this->setOrigin(trim($origin));
        $this->setUsername(trim($username));
        $this->setPassword(trim($password));
        $this->setLocale($locale);
        $this->setGoogleApikey($googleapikey);
        $this->setModus(($bTest) ? 'api-test' : 'api');
        $this->setUrl("https://" . $this->getModus() . ".montapacking.nl/rest/v5/");
        $this->setAddress($address['street'], $address['housenumber'], $address['housenumberaddition'], $address['postalcode'], $address['city'], $address['state'], $address['country']);

        $arr = array(
            'Origin' => $this->getOrigin(),
            'Currency' => 'EUR',
            'Language' => $this->getLocale(),
        );

        $this->setBasic($arr);

        $this->setShippers($this->refreshShippers());
        $this->setAllowedShippers($this->refreshAllowedShippers());
        $this->setPickupOptions($this->refreshPickupOptions());
        $this->setShippingOptions($this->refreshShippingOptions());
    }

    public function setDebug($value)
    {
        $this->debug = $value;
    }

    public function getDebug()
    {
        return (isset($this->debug)) ? $this->debug : false;
    }

    public function setOrigin($value)
    {
        $this->origin = $value;
    }

    public function getOrigin()
    {
        return (isset($this->origin)) ? $this->origin : null;
    }

    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function getUsername()
    {
        return (isset($this->username)) ? $this->username : null;
    }

    public function setPassword($value)
    {
        $this->password = $value;
    }

    public function getPassword()
    {
        return (isset($this->password)) ? $this->password : null;
    }

    public function setGoogleApikey($value)
    {
        $this->googleapikey = $value;
    }

    public function getGoogleApikey()
    {
        return (isset($this->googleapikey)) ? $this->googleapikey : null;
    }

    public function setUrl($value)
    {
        $this->url = $value;
    }

    public function getUrl()
    {
        return (isset($this->url)) ? $this->url : null;
    }

    public function setModus($value)
    {
        $this->modus = $value;
    }

    public function getModus()
    {
        return (isset($this->modus)) ? $this->modus : null;
    }

    public function setLocale($value)
    {
        $this->locale = $value;
    }

    public function getLocale()
    {
        return (isset($this->locale)) ? $this->locale : null;
    }

    public function setBasic($value)
    {
        $this->basic = $value;
    }

    public function getBasic()
    {
        return (isset($this->basic)) ? $this->basic : null;
    }

    public function setOrder($fTotalInc, $fTotalExcl)
    {
        $this->order = new Montapacking_Shipping_Objects_Order($fTotalInc, $fTotalExcl);
    }

    public function getOrder()
    {
        return (isset($this->order)) ? $this->order : null;
    }

    public function setAddress($street, $housenumber, $housenumberaddition, $postalcode, $city, $state, $countrycode)
    {
        $this->address = new Montapacking_Shipping_Objects_Address($street, $housenumber, $housenumberaddition, $postalcode, $city, $state, $countrycode, $this->getGoogleApikey());
    }

    public function getAddress()
    {
        return (isset($this->address)) ? $this->address : null;
    }

    public function setShippers($value)
    {
        $this->shippers = $value;
    }

    public function getShippers()
    {
        return (isset($this->shippers)) ? $this->shippers : null;
    }

    public function setProducts($value)
    {
        $this->products = $value;
    }

    public function getProducts()
    {
        return (isset($this->products)) ? $this->products : null;
    }

    public function setAllowedShippers($value)
    {
        $this->allowedshippers = $value;
    }

    public function getAllowedShippers()
    {
        return (isset($this->allowedshippers)) ? $this->allowedshippers : null;
    }

    public function setPickupOptions($value)
    {
        $this->pickupoptions = $value;
    }

    public function getPickupOptions()
    {
        return (isset($this->pickupoptions)) ? $this->pickupoptions : null;
    }

    public function setShippingOptions($value)
    {
        $this->shippingoptions = $value;
    }

    public function getShippingOptions()
    {
        return (isset($this->shippingoptions)) ? $this->shippingoptions : null;
    }
}