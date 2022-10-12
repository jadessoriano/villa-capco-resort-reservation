<?php

namespace App\Enums;

enum PackageName:string
{
    case morning = 'Morning';
    case evening = 'Evening';
    case wholeDay = 'Whole Day';
}