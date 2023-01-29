<?php
declare(strict_types=1);

namespace App\Models;

use App\AppConfig;
use App\Models\Enums\AccountStatus;
use App\Models\Interfaces\IMailAccount;
use App\Models\Traits\AccountSystemUtilsTrait;
use App\Models\Traits\RoundcubeLinkTrait;
use App\Utils\SysCommand;
use Sdk\Database\Exceptions\DatabaseObjectNotInitialized;
use Sdk\Database\MariaDB\Connection;

final readonly class PermanentAccount implements IMailAccount
{
    public string $emailAddress;

    public function __construct(public string $username, public AccountStatus $status)
    {
        $this->emailAddress = "$this->username@" . AppConfig::EMAIL_DOMAIN;
    }

    static function fromUsername(string $username): ?self
    {
        foreach (self::getAll() as $account) {
            if ($account->username === $username) {
                return $account;
            }
        }
        return null;
    }

    /**
     * @return self[]
     */
    public static function getAll(): array
    {
        $commandOutput = SysCommand::runString('/usr/bin/getent group mail | ' . "/usr/bin/awk -F ':' '{print $4}'");
        $groupAccounts = explode(',', $commandOutput);

        /**
         * @var self[] $mailObjects
         */
        $mailObjects = [];

        foreach ($groupAccounts as $account) {
            if (TemporaryAccount::exists($account)) {
                continue;
            }

            $mailObjects[] = new self($account, AccountStatus::CREATED);
        }

        $pendingAccounts = self::getPendingAccounts();
        return array_merge($mailObjects, $pendingAccounts);
    }

    public static function exists(string $username): bool
    {
        foreach (self::getAll() as $account) {
            if ($account->username === $username) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return self[]
     * @throws DatabaseObjectNotInitialized
     */
    private static function getPendingAccounts(): array
    {
        $query = Connection::query('SELECT * FROM Accounts WHERE expires IS NULL');
        $data = $query->fetch_all(1);

        /**
         * @var self[] $mailObjects
         */
        $mailObjects = [];

        foreach ($data as $row) {
            $status = AccountStatus::from(intval($row['status']));
            $mailObjects[] = new self($row['username'], $status);
        }

        return $mailObjects;
    }

    public static function create(string $username, string $password): void
    {
        Connection::query('INSERT INTO Accounts(name, password) VALUES(?,?)', [$username, $password]);
    }

    use RoundcubeLinkTrait;
    use AccountSystemUtilsTrait;
}