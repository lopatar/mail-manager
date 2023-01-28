<?php
declare(strict_types=1);

namespace App\Models;

use App\AppConfig;
use Utils\SysCommand;

class PermanentAccount
{
    public function __construct(public readonly string $username) {}

    /**
     * @return self[]
     */
    public static function getAll(): array
    {
        $commandOutput = SysCommand::runString('/usr/bin/getent mail group');
        $groupAccounts = explode(',', $commandOutput);

        /**
         * @var self[] $mailObjects
         */
        $mailObjects = [];

        for ($i = 0; $i < count($groupAccounts); $i++) {
            $username = $groupAccounts[$i];

            if ($i === 0) {
                $colonIndex = strpos($username, ':', -1);

                if ($colonIndex === false) {
                    continue;
                }

                $username = substr($username, $colonIndex + 1);
            }

            //TODO: Check if account is temporary!
            $mailObjects[] = new self($username);
        }

        return $mailObjects;
    }

    public function getMailAddress(): string
    {
        return "$this->username@" . AppConfig::EMAIL_DOMAIN;
    }
}