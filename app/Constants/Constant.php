<?php
namespace App\Constants;

class Constant
{
    const USER_ROLE = [
        'admin' => 1,
        'shop'  => 2,
        'user'  => 3
    ];

    const GENDER = [
        0 => 'Male',
        1 => 'Female',
    ];

    const ORDER_STATUS_NAME = [
        0 => 'Draft',
        1 => 'Bought',
        2 => 'Stop selling'
    ];

    const ORDER_STATUS = [
        'draft'         => 0,
        'bought'        => 1,
        'stop_selling'  => 2,
    ];
}