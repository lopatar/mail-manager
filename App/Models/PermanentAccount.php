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
        $commandOutput = SysCommand::runString('/usr/bin/getent group mail');
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
}