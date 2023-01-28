<?php
declare(strict_types=1);

namespace App\Models\Interfaces;

interface IMailAccount
{
    static function getAll(): array;
    static function exists(string $username): bool;
}