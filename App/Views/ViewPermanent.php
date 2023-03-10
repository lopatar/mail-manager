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
                <?php if ($account->isCreated()) { ?>
                    <?= $account->getManagementControls(true) ?>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<h3>Create permanent e-mail</h3>
<div>
    <form method="POST" action="/api/permanent/create">
        <div>
            <div>
                <label for="username">
                    Username
                </label>
            </div>
            <input type="text" id="username" name="username" maxlength="32" placeholder="Username" required>
        </div>
        <div>
            <div>
                <label for="password">
                    Password
                </label>
            </div>
            <input type="password" id="password" name="password" maxlength="64" minlength="24" placeholder="Password"
                   value="<?= Random::stringSafe(48) ?>" required>
        </div>

        <div>
            <button type="submit">Create</button>
        </div>
    </form>
    <button id="showPasswordBtn" onclick="showPassword()">Show password</button>
</div>

<script type="text/javascript">
    function showPassword() {
        const passwordField = document.getElementById('password');
        const passwordFieldType = passwordField.getAttribute('type');
        const showPasswordBtn = document.getElementById('showPasswordBtn');

        if (passwordFieldType === 'password') {
            passwordField.setAttribute('type', 'text');
            showPasswordBtn.innerText = 'Hide';
        } else {
            passwordField.setAttribute('type', 'password');
            showPasswordBtn.innerText = 'Show';
        }
    }
</script>
</body>
<footer>
    <a href="https://github.com/lopatar/mail-manager" target="_blank">Source code</a>
</footer>
</html>
