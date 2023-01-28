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
        return [];
    }
}