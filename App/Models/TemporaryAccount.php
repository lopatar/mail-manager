<?php
declare(strict_types=1);

namespace App\Models;

use App\AppConfig;
use App\Models\Enums\AccountStatus;
use App\Models\Interfaces\IMailAccount;
use App\Models\Traits\AccountSystemUtilsTrait;
use App\Models\Traits\RoundcubeLinkTrait;
use Sdk\Database\Exceptions\DatabaseObjectNotInitialized;
use Sdk\Database\MariaDB\Connection;

final readonly class TemporaryAccount implements IMailAccount
{
    public string $emailAddress;

    public function __construct(public string $username, public string $password, public int $expiresTimestamp, public AccountStatus $status)
    {
        $this->emailAddress = "$this->username@" . AppConfig::EMAIL_DOMAIN;
    }

    public static function exists(string $username): bool
    {
        return Connection::query('SELECT name FROM Accounts WHERE name=?', [$username])->num_rows === 1;
    }

    public static function fromUsername(string $username): ?self
    {
        $query = Connection::query('SELECT * FROM Accounts WHERE name=?', [$username]);

        if ($query->num_rows === 0) {
            return null;
        }

        $data = $query->fetch_assoc();

        $expires = intval($data['expires']);
        $status = AccountStatus::from($expires);

        if (time() >= $expires) {
            $status = AccountStatus::WAITING_FOR_DELETION;
        }

        return new self($data['name'], $data['password'], $expires, $status);
    }

    /**
     * @return self[]
     * @throws DatabaseObjectNotInitialized
     */
    public static function getAll(): array
    {
        $query = Connection::query('SELECT * FROM Accounts');
        $data = $query->fetch_all(1);

        /**
         * @var self[] $temporaryObjects
         */
        $temporaryObjects = [];

        foreach ($data as $row) {
            $expires = intval($row['expires']);
            $status = AccountStatus::from($expires);

            if (time() >= $expires) {
                $status = AccountStatus::WAITING_FOR_DELETION;
            }

            $temporaryObjects[] = new self($row['name'], $row['password'], $expires, $status);
        }

        return $temporaryObjects;
    }

    public function expiresString(): string
    {
        if ($this->isExpired()) {
            return 'Expired';
        }

        return date('j. n. Y', $this->expiresTimestamp);
    }

    public function isExpired(): bool
    {
        return time() >= $this->expiresTimestamp;
    }

    use RoundcubeLinkTrait;
    use AccountSystemUtilsTrait;
}