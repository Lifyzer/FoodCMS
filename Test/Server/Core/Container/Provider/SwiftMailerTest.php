<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018-2020, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Test\Server\Core\Container\Provider;

use Lifyzer\Server\Core\Container\Provider\Providable;
use Lifyzer\Server\Core\Container\Provider\SwiftMailer;
use Phake;
use Phake_IMock;
use PHPUnit\Framework\TestCase;
use Swift_Mailer;

class SwiftMailerTest extends TestCase
{
    public function testGetService(): void
    {
        /** @var Providable|Phake_IMock $swiftMailer */
        $swiftMailer = Phake::mock(SwiftMailer::class);
        $this->assertInstanceOf(Swift_Mailer::class, $swiftMailer->getService());
    }
}
