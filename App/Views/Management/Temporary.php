<?php
declare(strict_types=1);

use App\Models\TemporaryAccount;
use Sdk\Render\View;

/**
 * @var View $this
 * @var TemporaryAccount $account
 */

$account = $this->getProperty('account');

?>
<a href="/temporary"><- Go back</a> | <a href="/permanent">View permanent</a>
<h3>Managing temporary e-mail</h3>
<h4><?= $account->emailAddress ?></h4>
</body>
</html>

