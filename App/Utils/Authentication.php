<?php
declare(strict_types=1);

namespace App\Utils;

use Sdk\Database\Exceptions\DatabaseObjectNotInitialized;
use Sdk\Database\MariaDB\Connection;

abstract class Authentication
{
    /**
     * @return array = [
     *    0 => ['username' => 'username', 'password' => 'passwordHash'],
     *    1 => ['username' => 'username2', 'password' => 'passwordHash2'],
     * ] User credentials in the annotation format, passwords are meant to be protected using {@see password_hash() or @see}
     * @throws DatabaseObjectNotInitialized
     */
    public static function getUsers(): array
    {
        $query = Connection::query('SELECT * FROM PanelUsers');
        return $query->fetch_all(1);
    }
}