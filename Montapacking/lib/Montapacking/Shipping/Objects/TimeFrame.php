<?php

class Montapacking_Shipping_Objects_TimeFrame
{

    public $from;
    public $to;
    public $code;
    public $description;
    public $options = [];
    public $sellprice;

    public function __construct($from, $to, $code, $description, $options, $sellprice)
    {
        $this->setFrom($from);
        $this->setTo($to);
        $this->setCode($code);
        $this->setDescription($description);
        $this->setOptions($options);
        $this->setSellPrice($sellprice);

    }

    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setOptions($options)
    {
        $list = null;

        if (is_array($options)) {

            foreach ($options as $onr => $option) {

                $list[$onr] = new Montapacking_Shipping_Objects_ShippingOption(
                    $option->Code,
                    $option->ShipperCodes,
                    $option->ShipperOptionCodes,
                    $option->ShipperOptionsWithValue,
                    $option->Description,
                    $option->IsMailbox,
                    $option->SellPrice,
                    $option->SellPriceCurrency,
                    $option->From,
                    $option->To,
                    $option->Options,
                    $option->ShippingDeadline
                );

            }

        }

        $this->options = $list;
        return $this;

    }

    public function setSellPrice($sellprice)
    {
        $this->sellprice = $sellprice;
        return $this;
    }

    public function toArray()
    {

        $option = null;
        foreach ($this as $key => $value) {
            $option[$key] = $value;
        }

        return $option;

    }

}