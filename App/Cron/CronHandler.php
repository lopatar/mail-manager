<?php
declare(strict_types=1);

use App\Models\Enums\AccountStatus;
use App\Models\PermanentAccount;
use App\Models\TemporaryAccount;
use App\SdkConfig;
use Sdk\Database\MariaDB\Connection;

require __DIR__ . '/../../vendor/autoload.php';

$query = Connection::query('SELECT * FROM Accounts');
$data = $query->fetch_all(1);

$config = new SdkConfig();
Connection::init($config->getMariaDbHost(), $config->getMariaDbUsername(), $config->getMariaDbPassword(), $config->getMariaDbDatabaseName());

foreach ($data as $row) {
    $isPermanent = is_null($row['expires']);
    $username = $row['user'];

    $account = ($isPermanent) ? PermanentAccount::fromUsername($username) : TemporaryAccount::fromUsername($username);

    if ($account === null) {
        continue;
    }

    switch ($account->status) {
        case AccountStatus::WAITING_FOR_CREATION:
            $account->createSystemUser($isPermanent);
            break;
        case AccountStatus::WAITING_FOR_DELETION:
            $account->deleteSystemUser($isPermanent);
            break;
        case AccountStatus::CREATED:
            break;
    }
}

