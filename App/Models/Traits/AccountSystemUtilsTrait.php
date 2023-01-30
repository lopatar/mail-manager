<?php
declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Enums\AccountStatus;
use App\Utils\SysCommand;
use Sdk\Database\MariaDB\Connection;

trait AccountSystemUtilsTrait
{
    public function createSystemUser(bool $permanentAccount, string $permanentPassword = ''): void
    {
        if ($permanentAccount) {
            Connection::query('DELETE FROM Accounts WHERE name=?', [$this->username]);
        } else {
            Connection::query('UPDATE Accounts SET status=? WHERE name=?', [AccountStatus::CREATED->value, $this->username], 'is');
        }

        if ($this->systemUserExists()) {
            return;
        }

        $password = ($permanentAccount) ? $permanentPassword : $this->password;
        $passwordHash = SysCommand::runString("openssl passwd -1 $password");

        SysCommand::run("/usr/sbin/useradd -G mail -m -p $passwordHash $this->username");
    }

    public function systemUserExists(): bool
    {
        $commandOutput = SysCommand::runString("/usr/bin/id -u $this->username");
        return !str_contains($commandOutput, 'no such user');
    }

    public function deleteSystemUser(bool $isPermanent): void
    {
        if (!$isPermanent) {
            Connection::query('DELETE FROM Accounts WHERE name=?', [$this->username]);
        }

        if (!$this->systemUserExists()) {
            return;
        }

        SysCommand::run("/usr/sbin/deluser --remove-all-files $this->username");
    }
}