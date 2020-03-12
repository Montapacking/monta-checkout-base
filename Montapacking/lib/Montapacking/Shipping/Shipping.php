<?php

class Montapacking_Shipping_Shipping extends Montapacking_Shipping_ShippingAbstract
{
    public function addProduct($sku, $quantity, $length = 0, $width = 0, $height = 0, $weight = 0)
    {
        $this->products['products'][] = new Montapacking_Shipping_Objects_Product($sku, $length, $width, $height, $weight, $quantity);
    }

    public function refreshShippers()
    {
        $origins = null;

        $result = $this->call('info');

        if (isset($result->Origins)) {

            ## Array goedzetten
            if (is_array($result->Origins)) {
                $origins = $result->Origins;
            } else {
                $origins[] = $result->Origins;
            }
        }

        return $origins;
    }


    public function refreshAllowedShippers()
    {
        $shippers = $this->getShippers();

        $allowedshippers = array();

        if (null !== $shippers) {

            $shippers = $this->getShippers()[0]->ShipperOptions;

            foreach ($shippers as $key => $values) {
                $shipper_code = $values->ShipperCode;
                $ispickuppoint = $values->IsPickupPoint;

                if (true === $ispickuppoint) {
                    $allowedshippers[] = $shipper_code;
                }
            }

        }
        return $allowedshippers;
    }


    public function refreshPickupOptions($onstock = true, $mailbox = false, $mailboxfit = false, $trackingonly = false, $insurance = false)
    {

        $params = array();
        $params['OnlyPickupPoints'] = 'true';
        //$params['MaxNumberOfPickupPoints'] = 3;
        $params['ProductsOnStock'] = ($onstock) ? 'TRUE' : 'FALSE';
        $params['MaiboxShipperMandatory'] = $mailbox;
        $params['TrackingMandatory'] = $trackingonly;
        $params['InsuranceRequired'] = $insurance;
        $params['ShipmentFitsThroughDutchMailbox'] = $mailboxfit;

        foreach ($this->getAllowedShippers() as $key => $value) {
            $params['AllowedShippers[' . $key . ']'] = $value;
        }

        ## Timeframes omzetten naar bruikbaar object

        $result = $this->call('ShippingOptions', '', $params);


        if (isset($result->Timeframes)) {

            ## Shippers omzetten naar shipper object
            foreach ($result->Timeframes as $timeframe) {

                $pickups[] = new Montapacking_Shipping_Objects_PickupPoint(
                    $timeframe->From,
                    $timeframe->To,
                    $timeframe->TypeCode,
                    $timeframe->PickupPointDetails,
                    $timeframe->ShippingOptions
                );

            }

        }

        //print "<pre>";
        //print_r($pickups);
        //print "</pre>";
        //exit;

        return $pickups;

    }


    public function refreshShippingOptions($onstock = true, $mailbox = false, $mailboxfit = false, $trackingonly = false, $insurance = false)
    {


        $params = array();
        $params['ProductsOnStock'] = ($onstock) ? 'TRUE' : 'FALSE';
        $params['MaiboxShipperMandatory'] = $mailbox;
        $params['TrackingMandatory'] = $trackingonly;
        $params['MaxNumberOfPickupPoints'] = 0;
        $params['InsuranceRequired'] = $insurance;
        $params['ShipmentFitsThroughDutchMailbox'] = $mailboxfit;


        $timeframes = null;

        ## Timeframes omzetten naar bruikbaar object
        $result = $this->call('ShippingOptions', '', $params);

        if (isset($result->Timeframes)) {

            ## Shippers omzetten naar shipper object
            foreach ($result->Timeframes as $timeframe) {

                $lowest_price = 999999999;
                foreach ($timeframe->ShippingOptions as $obj) {
                    //var_dump($obj);
                    if ($obj->SellPrice < $lowest_price && $lowest_price > 0) {
                        $lowest_price = $obj->SellPrice;
                    }
                }


                $timeframes[] = new Montapacking_Shipping_Objects_TimeFrame(
                    $timeframe->From,
                    $timeframe->To,
                    $timeframe->TypeCode,
                    $timeframe->TypeDescription,
                    $timeframe->ShippingOptions,
                    $lowest_price
                );

            }

        }

        return $timeframes;

    }


    public function call($method, $send = null, $params = array(), $debug = false)
    {
        $request = '?1=1';
        $request .= '&' . http_build_query($this->getBasic());
        $request .= '&' . http_build_query($this->getAddress()->toArray());

        if (count($params)) {
            $request .= '&' . http_build_query($params);
        }

        if ($send != null) {
            foreach ($send as $data) {

                if (isset($this->{$data}) && $this->{$data} != null) {

                    if (!is_array($this->{$data})) {
                        $request .= '&' . http_build_query($this->{$data}->toArray());

                    } else {
                        $request .= '&' . http_build_query($this->{$data});
                    }
                }
            }
        }


        $method = strtolower($method);
        $url = $this->url . $method;

        if ($this->debug || true === $debug) {
            echo $url;

            $temp_request = $request;
            $temp_request = str_replace('%5B', "[", $temp_request);
            $temp_request = str_replace('%5D', "]", $temp_request);
            echo $temp_request . "<br>";
            //echo str_replace('&', "$\n", $request);
        }

        $ch = curl_init();

        $this->password = htmlspecialchars_decode($this->password);


        curl_setopt($ch, CURLOPT_URL, $url . '?' . $request);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        $result = curl_exec($ch);

        $decoded = json_decode($result);
        if (isset($decoded->Message)) {
            echo $decoded->Message;
            exit;
        }

        if ($this->debug || true === $debug) {
            echo '<pre>';
            print_r($decoded);
            echo '<pre>';
        }

        curl_close($ch);
        return $decoded;

    }
}