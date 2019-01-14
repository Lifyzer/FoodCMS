<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Test\Server\Core\Container\Provider;

use Lifyzer\Server\Core\Container\Provider\HttpRequest;
use Lifyzer\Server\Core\Container\Provider\Providable;
use Phake;
use Phake_IMock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class HttpRequestTest extends TestCase
{
    public function testGetService(): void
    {
        /** @var Providable|Phake_IMock $httpRequest */
        $httpRequest = Phake::mock(HttpRequest::class);
        $this->assertInstanceOf(Request::class, $httpRequest->getService());
    }
}
