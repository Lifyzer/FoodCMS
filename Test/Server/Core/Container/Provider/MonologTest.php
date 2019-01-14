<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Test\Server\Core\Container\Provider;

use Lifyzer\Server\Core\Container\Provider\Monolog;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class MonologTest extends TestCase
{
    private const LOGGING_CHANNEL = 'errors';

    public function testGetService(): void
    {
        $monolog = new Monolog(self::LOGGING_CHANNEL);
        $this->assertInstanceOf(LoggerInterface::class, $monolog->getService());
    }
}
