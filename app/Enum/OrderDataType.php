<?php

namespace App\Enum;

enum OrderDataType: string
{
    /** History status and actions data */
    case HISTORY = 'H';
    /** Payment data */
    case PAYMENT = 'P';
    /** Shipping data */
    case SHIPPING = 'S';
}
