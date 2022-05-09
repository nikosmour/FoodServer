<?php

namespace App\Enum;

use App\Traits\EnumToArrayTrait;

enum CardStatusEnum: string
{
    use EnumToArrayTrait;

    case TEMPORARY_SAVED = 'temporary saved';
    case SUBMITTED = 'submitted';
    case CHECKING = 'checking';
    case TEMPORARY_CHECKED = 'temporary checked';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case INCOMPLETE = 'incomplete';
}
