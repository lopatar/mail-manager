<?php
declare(strict_types=1);

/**
 * @var View $this
 * @var PermanentAccount[] $accounts
 */

use App\Models\PermanentAccount;
use Sdk\Render\View;
use Sdk\Utils\Random;

$accounts = $this->getProperty('accounts');
?>
<a href="/"><- Go home</a> | <a href="/temporary">View temporary</a>
<h3>View permanent e-mails</h3>
<table>
    <thead>
    <tr>
        <th>E-mail address</th>
        <th>Status</th>
        <th>Options</th>
    </tr>
    </thead>

    <tbody>
    <?php
    foreach ($accounts as $account) {
        ?>
        <tr>
            <td><?= $account->emailAddress ?></td>
            <td><?= $account->status->name ?></td>
            <td>
                <?= $account->getRoundcubeLink() ?>
                <a href="/manage/<?= $account->username ?>">Manage</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<h3>Create permanent e-mail</h3>
<form method="POST" action="/api/permanent/create">
    <div>
        <input type="text" name="username" maxlength="32" placeholder="Username" required>
    </div>
    <div>
        <input type="password" name="password" max="64" placeholder="Password" value="<?= Random::stringSafe(32) ?>" required>
    </div>

    <div>
        <button type="submit">Create</button>
    </div>
</form>
</body>
</html>
