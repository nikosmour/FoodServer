<?php

namespace App\Enum;

use App\Interfaces\Enum;
use App\Traits\Enums\EnumTrait;

enum CardStatusEnum: string implements Enum
{
    use EnumTrait;

    case TEMPORARY_SAVED = 'temporary saved';
    case SUBMITTED = 'submitted';
    case CHECKING = 'checking';
    case TEMPORARY_CHECKED = 'temporary checked';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case INCOMPLETE = 'incomplete';
}
