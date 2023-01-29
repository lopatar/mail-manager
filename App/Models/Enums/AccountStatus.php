<?php
declare(strict_types=1);

namespace App\Models\Enums;

enum AccountStatus: int
{
    case WAITING_FOR_CREATION = 0;
    case CREATED = 1;
    case WAITING_FOR_DELETION = 2;
}
