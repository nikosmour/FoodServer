<?php

namespace App\Enum;

use App\Traits\EnumToArrayTrait;

enum CardDocumentStatusEnum: string
{
    use EnumToArrayTrait;

    case SUBMITTED = 'submitted';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case INCOMPLETE = 'incomplete';
}
