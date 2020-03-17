<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018-2020, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Server\Core\Container\Provider;

use Swift_Mailer;
use Swift_SendmailTransport;

class SwiftMailer implements Providable
{
    public function getService(): Swift_Mailer
    {
        $transport = new Swift_SendmailTransport();

        return new Swift_Mailer($transport);
    }
}
