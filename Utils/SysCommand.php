<?php
declare(strict_types=1);

namespace Utils;

abstract class SysCommand
{
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

    public static function runString(string $command): string
    {
        return self::run($command)[0];
    }
}