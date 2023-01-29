<?php
declare(strict_types=1);

namespace App\Models\Interfaces;

interface IMailAccount
{
    static function getAll(): array;

    static function exists(string $username): bool;

    static function fromUsername(string $username): ?self;

    function getRoundcubeLink(): string;
    function systemUserExists(): bool;
}