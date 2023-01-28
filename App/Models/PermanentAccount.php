<?php
declare(strict_types=1);

namespace App\Models;

use App\AppConfig;
use App\Utils\SysCommand;

readonly class PermanentAccount
{
    public string $emailAddress;
    public function __construct(public string $username)
    {
        $this->emailAddress = "$this->username@" . AppConfig::EMAIL_DOMAIN;
    }

    /**
     * @return self[]
     */
    public static function getAll(): array
    {
        $commandOutput = SysCommand::runString('/usr/bin/getent group mail | /usr/bin/awk -F' . "':' '{print $3}'");
        $groupAccounts = explode(',', $commandOutput);

        /**
         * @var self[] $mailObjects
         */
        $mailObjects = [];

        foreach ($groupAccounts as $account) {
            //TODO: Check if temporary
            $mailObjects[] = new self($account);
        }

        return $mailObjects;
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
}