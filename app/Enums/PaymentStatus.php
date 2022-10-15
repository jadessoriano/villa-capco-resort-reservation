<?php

namespace App\Enums;

enum PaymentStatus:string
{
    case unpaid = 'Unpaid';
    case paid = 'Paid';
}