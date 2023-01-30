<?php
declare(strict_types=1);

namespace App\Models;

use App\AppConfig;
use App\Models\Enums\AccountStatus;
use App\Models\Interfaces\IMailAccount;
use App\Models\Traits\AccountManageUtils;
use App\Models\Traits\AccountSystemUtilsTrait;
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
        return Connection::query('SELECT name FROM Accounts WHERE name=? AND expires IS NOT NULL', [$username])->num_rows === 1;
    }

    public static function fromUsername(string $username): ?self
    {
        $query = Connection::query('SELECT * FROM Accounts WHERE name=? AND expires IS NOT NULL', [$username]);

        if ($query->num_rows === 0) {
            return null;
        }

        $data = $query->fetch_assoc();

        $expires = intval($data['expires']);
        $status = AccountStatus::from($data['status']);

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
        $query = Connection::query('SELECT * FROM Accounts WHERE expires IS NOT NULL');
        $data = $query->fetch_all(1);

        /**
         * @var self[] $temporaryObjects
         */
        $temporaryObjects = [];

        foreach ($data as $row) {
            $expires = intval($row['expires']);
            $status = AccountStatus::from(intval($row['status']));

            if (time() >= $expires) {
                $status = AccountStatus::WAITING_FOR_DELETION;
            }

            $temporaryObjects[] = new self($row['name'], $row['password'], $expires, $status);
        }

        return $temporaryObjects;
    }

    public static function create(string $username, string $password, int $expirationMinutes)
    {
        $expirationTimestamp = time() + ($expirationMinutes * 60);
        Connection::query('INSERT INTO Accounts(name, password, expires, status) VALUES(?,?,?,?)', [$username, $password, $expirationTimestamp, AccountStatus::WAITING_FOR_CREATION->value], 'ssii');
    }

    public function expiresString(): string
    {
        if ($this->isExpired()) {
            return 'Expired';
        }

        return date('d.m.Y H:i:s', $this->expiresTimestamp);
    }

    public function isExpired(): bool
    {
        return time() >= $this->expiresTimestamp;
    }

    use AccountManageUtils;
    use AccountSystemUtilsTrait;
}