<?php

namespace App\Enums;

enum ExtensionStatus:string
{
    case open = 'Available For Request';
    case unavailable = 'Unavailable';
    case confirming = 'Pending';
    case approved = 'Approved';
}