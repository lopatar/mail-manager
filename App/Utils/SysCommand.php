<?php
declare(strict_types=1);

namespace App\Utils;
abstract class SysCommand
{
    public static function runString(string $command): string
    {
        return self::run($command)[0];
    }

    /**
     * @return string[]
     */
    public static function run(string $command): array
    {
        $command .= ' 2>&1';

        $output = [];
        exec($command, $output);
        return $output;
    }
}