<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\Interfaces\IMailAccount;
use Sdk\Database\MariaDB\Connection;

final readonly class TemporaryAccount implements IMailAccount
{
    public function __construct(public string $username, public string $password, public int $expiresTimestamp) {}

    public static function exists(string $username): bool {
        return Connection::query('SELECT name FROM tempAccounts WHERE name=?', [$username])->num_rows === 1;
    }

    /**
     * @return self[]
     */
    public static function getAll(): array
    {
        return [];
    }
}