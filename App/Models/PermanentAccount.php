<?php
declare(strict_types=1);

namespace App\Models;

class PermanentAccount
{
    public function __construct(public readonly string $username) {}

    /**
     * @return self[]
     */
    public function getAll(): array
    {
        return [];
    }
}