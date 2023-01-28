<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\Interfaces\IMailAccount;
use Sdk\Database\Exceptions\DatabaseObjectNotInitialized;
use Sdk\Database\MariaDB\Connection;

final readonly class TemporaryAccount implements IMailAccount
{
    public function __construct(public string $username, public string $password, public int $expiresTimestamp) {}

    public static function exists(string $username): bool {
        return Connection::query('SELECT name FROM tempAccounts WHERE name=?', [$username])->num_rows === 1;
    }

    /**
     * @return self[]
     * @throws DatabaseObjectNotInitialized
     */
    public static function getAll(): array
    {
        $query = Connection::query('SELECT * FROM tempAccounts');
        $data = $query->fetch_all(1);

        /**
         * @var self[] $temporaryObjects
         */
        $temporaryObjects = [];

        foreach ($data as $row) {
            $temporaryObjects[] = new self($data['name'], $data['password'], intval($data['expires']));
        }

        return $temporaryObjects;
    }
}