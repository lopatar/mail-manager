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
        $passwordHash = SysCommand::runString("openssl passwd -1 $password");

        var_dump($password);

        $data = SysCommand::run("/usr/sbin/useradd -G mail -m -p $passwordHash $this->username");

        var_dump($data);

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

    public function deleteSystemUser(bool $isPermanent): void
    {
        if (!$this->systemUserExists()) {
            return;
        }

        $data = SysCommand::run("/usr/sbin/deluser --remove-all-files $this->username");

        var_dump($data);

        if (!$isPermanent) {
            Connection::query('DELETE FROM Accounts WHERE name=?', [$this->username]);
        }
    }
}