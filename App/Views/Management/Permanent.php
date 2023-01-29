<?php
declare(strict_types=1);

use App\Models\PermanentAccount;
use Sdk\Render\View;

/**
 * @var View $this
 * @var PermanentAccount $account
 */

$account = $this->getProperty('account');
?>
<a href="/permanent"><- Go back</a> | <a href="/temporary">View temporary</a>
<h3>Managing permanent e-mail</h3>
<h4><?= $account->emailAddress ?></h4>
</body>
</html>
