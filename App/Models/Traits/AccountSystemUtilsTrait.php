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
        if ($this->systemUserExists()) {
            return;
        }

        $password = ($permanentAccount) ? $permanentPassword : $this->password;
        SysCommand::run("/usr/sbin/useradd -G mail -m -p $(openssl passwd -1 $password) $this->username");

        if ($permanentAccount) {
            Connection::query('DELETE FROM Accounts WHERE name=?', [$this->username]);
        } else {
            Connection::query('UPDATE Accounts SET status=? WHERE name=?', [AccountStatus::CREATED->value, $this->username], 'is');
        }
    }

    public function systemUserExists(): bool
    {
        $commandOutput = SysCommand::runString("/usr/bin/id -u $this->username");
        return !str_contains($commandOutput, 'no such user');
    }

    public function changePassword(string $password): void
    {
        SysCommand::run("/usr/bin/echo -e \"$password\\n$password\" | /usr/bin/passwd $this->username");
    }

    public function deleteSystemUser(): void
    {
        if (!$this->systemUserExists()) {
            return;
        }

        SysCommand::run("/usr/sbin/deluser --remove-all-files $this->username");

        Connection::query('DELETE FROM Accounts WHERE name=?', [$this->username]);
    }
}