<?php

namespace App\Enum;

use App\Traits\EnumToArrayTrait;

enum UserRoleEnum: string
{
    use EnumToArrayTrait;

    case STUDENT = 'student';
    case RESEARCHER = 'researcher';
    case STAFF_COUPON = 'staff coupon';
    case STAFF_CARD = 'staff card application';
    case STAFF_ENTRY = 'staff entry';
}
