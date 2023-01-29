<?php
declare(strict_types=1);

/**
 * @var View $this
 * @var TemporaryAccount[] $accounts
 */

use App\AppConfig;
use App\Models\TemporaryAccount;
use Sdk\Render\View;

$accounts = $this->getProperty('accounts');
?>
<a href="/"><- Go home</a> | <a href="/permanent">View permanent</a>
<h3>View temporary e-mails</h3>
<table>
    <thead>
    <tr>
        <th>E-mail address</th>
        <th>Password</th>
        <th>Expires</th>
        <th>Status</th>
        <th>Options</th>
    </tr>
    </thead>

    <tbody>
    <?php foreach ($accounts as $account) { ?>
        <tr>
            <td><?= $account->emailAddress ?></td>
            <td><?= $account->password ?></td>
            <td><?= $account->expiresString() ?></td>
            <td><?= $account->status->name ?></td>
            <td>
                <?= $account->getRoundcubeLink() ?>
                <a href="/manage-temp/<?= $account->username ?>">Manage</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<h3>Create temporary e-mail</h3>
<form method="POST" action="/api/temporary/create">
    <div>
        <div>
            <label for="username">
                Username
            </label>
        </div>
        <input type="text" id="username" name="username" maxlength="32" placeholder="Username - empty for random">
    </div>

    <div>
        <div>
            <label for="expirationMinutes">
                Expiration (minutes)
            </label>
        </div>
        <input type="number" id="expirationMinutes" name="expirationMinutes" placeholder="Expiration minutes" min="30"
               max="<?= AppConfig::MAX_TEMPORARY_EMAIL_MINUTES ?>" value="30" required>
    </div>

    <div>
        <button type="submit">Create</button>
    </div>
</form>
</body>
</html>
