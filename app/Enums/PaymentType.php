<?php

namespace App\Enums;

enum PaymentType:string
{
    case cod = 'Cash';
    case paypal = 'Paypal';
}