<?php

namespace App;

class StripeService
{

    private $privatekey;
    public function __construct()
    {
        $this ->privatekey = $_ENV['STRIPE_SECRET_KEY_TEST'];
    }


}