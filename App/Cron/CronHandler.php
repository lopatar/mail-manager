<?php
declare(strict_types=1);

use App\Models\Enums\AccountStatus;
use App\Models\TemporaryAccount;
use Sdk\Database\MariaDB\Connection;

require '../../vendor/autoload.php';

$query = Connection::query('SELECT * FROM Accounts');
$data = $query->fetch_all(1);

foreach ($data as $row) {
    if ($row['expires'] === null) {

        continue;
    }

    $account = TemporaryAccount::fromUsername($row['user']);

    switch ($account->status) {
        case AccountStatus::WAITING_FOR_CREATION:
            $account->createSystemUser(false);
            break;
        case AccountStatus::WAITING_FOR_DELETION:
            $account->deleteSystemUser();
            break;
    }
}

