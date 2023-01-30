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
    echo "isNull : $isPermanent" . PHP_EOL;
    $username = $row['name'];
    echo "username : $isPermanent" . PHP_EOL;

    $account = ($isPermanent) ? PermanentAccount::fromUsername($username) : TemporaryAccount::fromUsername($username);

    echo "account: " . PHP_EOL;
    var_dump($account);

    if ($account === null) {
        continue;
    }

    switch ($account->status) {
        case AccountStatus::WAITING_FOR_CREATION:
            echo 'CREATION' . PHP_EOL;
            $account->createSystemUser($isPermanent);
            break;
        case AccountStatus::WAITING_FOR_DELETION:
            echo 'DELETION' . PHP_EOL;
            $account->deleteSystemUser($isPermanent);
            break;
        case AccountStatus::CREATED:
            echo 'ACC_CREATED?' . PHP_EOL;
            break;
    }
}

