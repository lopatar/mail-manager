<?php
declare(strict_types=1);

use App\Models\Enums\AccountStatus;
use App\Models\PermanentAccount;
use App\Models\TemporaryAccount;
use App\SdkConfig;
use Sdk\Database\MariaDB\Connection;

require __DIR__ . '/../../vendor/autoload.php';

$config = new SdkConfig();
Connection::init($config->getMariaDbHost(), $config->getMariaDbUsername(), $config->getMariaDbPassword(), $config->getMariaDbDatabaseName());

$query = Connection::query('SELECT * FROM Accounts');
$data = $query->fetch_all(1);

foreach ($data as $row) {
    $isPermanent = is_null($row['expires']);
    $username = $row['name'];

    $account = ($isPermanent) ? PermanentAccount::fromUsername($username) : TemporaryAccount::fromUsername($username);

    if ($account === null) {
        continue;
    }

    echo 'acc not null';
    var_dump($account);

    switch ($account->status) {
        case AccountStatus::WAITING_FOR_CREATION:
            echo 'create';
            var_dump($account);
            $account->createSystemUser($isPermanent);
            break;
        case AccountStatus::WAITING_FOR_DELETION:
            echo 'deleete';
            var_dump($account);
            $account->deleteSystemUser($isPermanent);
            break;
        case AccountStatus::CREATED:
            break;
    }
}

