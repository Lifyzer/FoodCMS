<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Test\Server\Core\Container\Provider;

use Lifyzer\Server\Core\Container\Provider\Providable;
use Lifyzer\Server\Core\Container\Provider\Twig;
use Phake;
use PHPUnit\Framework\TestCase;
use Twig_Environment;

class TwigTest extends TestCase
{
    public function testGetService(): void
    {
        /** @var Providable|Phake_IMock $twig */
        $twig = Phake::mock(Twig::class);
        $this->assertInstanceOf(Twig_Environment::class, $twig->getService());
    }
}
