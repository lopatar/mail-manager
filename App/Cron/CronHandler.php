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

var_dump($data);

foreach ($data as $row) {
    $isPermanent = is_null($row['expires']);
    $username = $row['name'];

    $account = ($isPermanent) ? PermanentAccount::fromUsername($username) : TemporaryAccount::fromUsername($username);

    var_dump($account);

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

