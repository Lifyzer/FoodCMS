<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Server\Core\Container\Provider;

use Symfony\Component\HttpFoundation\Request;

class HttpRequest implements Providable
{
    public function getService(): Request
    {
        return Request::createFromGlobals();
    }
}
