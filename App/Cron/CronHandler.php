<?php
declare(strict_types=1);

use App\Models\Enums\AccountStatus;
use App\Models\PermanentAccount;
use App\Models\TemporaryAccount;
use Sdk\Database\MariaDB\Connection;

require '../../vendor/autoload.php';

$query = Connection::query('SELECT * FROM Accounts');
$data = $query->fetch_all(1);

foreach ($data as $row) {
    $isPermanent = is_null($row['expires']);
    $username = $row['user'];

    $account = ($isPermanent) ? PermanentAccount::fromUsername($username) : TemporaryAccount::fromUsername($username);

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

