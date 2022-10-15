<?php

namespace App\Enums;

enum PaymentType:string
{
    case cod = 'Cash On Delivery';
    case paypal = 'Paypal';
}