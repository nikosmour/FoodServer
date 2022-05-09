<?php

namespace App\Enum;

use App\Interfaces\Enum;
use App\Traits\Enums\EnumTrait;

enum CardDocumentStatusEnum: string implements Enum
{
    use EnumTrait;

    case SUBMITTED = 'submitted';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case INCOMPLETE = 'incomplete';
}
