<?php
declare(strict_types=1);

namespace App\Models\Traits;

use App\Utils\SysCommand;
use Sdk\Database\MariaDB\Connection;

trait AccountSystemUtilsTrait
{
    public function createSystemUser(): void
    {
        if ($this->systemUserExists()) {
            return;
        }

    }

    public function systemUserExists(): bool
    {
        $commandOutput = SysCommand::runString("/usr/bin/id -u $this->username");
        return !str_contains($commandOutput, 'no such user');
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