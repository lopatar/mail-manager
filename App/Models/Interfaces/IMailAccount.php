<?php
declare(strict_types=1);

namespace App\Models\Interfaces;

interface IMailAccount
{
    static function getAll(): array;

    static function exists(string $username): bool;

    static function fromUsername(string $username): ?self;

    function getManagementControls(bool $permanentAccount): string;

    function isCreated(): bool;

    function createSystemUser(bool $permanentAccount): void;

    function systemUserExists(): bool;

    function deleteSystemUser(): void;

    function scheduleDeletion(bool $permanentAccount): void;
}