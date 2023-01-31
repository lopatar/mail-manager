<?php
declare(strict_types=1);

namespace App\Models;

use App\AppConfig;
use App\Models\Enums\AccountStatus;
use App\Models\Interfaces\IMailAccount;
use App\Models\Traits\AccountManageUtils;
use App\Models\Traits\AccountSystemUtilsTrait;
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
        //TODO: Refactor
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

        $pendingAccounts = self::getPendingAccounts($mailObjects);
        return array_merge($mailObjects, $pendingAccounts);
    }

    /**
     * @param string $username
     * @param PermanentAccount[] $accounts
     * @return bool
     */
    public static function exists(string $username, ?array $accounts = null): bool
    {
        $accounts = $accounts ?? self::getAll();

        foreach ($accounts as $account) {
            if ($account->username === $username) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param self[] $systemAccounts
     * @return self[]
     * @throws DatabaseObjectNotInitialized
     */
    private static function getPendingAccounts(array $systemAccounts): array
    {
        $query = Connection::query('SELECT * FROM Accounts WHERE expires IS NULL');
        $data = $query->fetch_all(1);

        /**
         * @var self[] $mailObjects
         */

        $mailObjects = [];

        foreach ($data as $row) {
            $username = $row['name'];

            if (self::exists($username, $systemAccounts)) {
                continue;
            }

            $mailObjects[] = new self($username, AccountStatus::from(intval($row['status'])));
        }

        return $mailObjects;
    }

    public static function create(string $username, string $password): void
    {
        Connection::query('INSERT INTO Accounts(name, password) VALUES(?,?)', [$username, $password]);
    }

    use AccountManageUtils;
    use AccountSystemUtilsTrait;
}