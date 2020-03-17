<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018-2020, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Test\Server\Core\Container;

use Lifyzer\Server\Core\Container\Container;
use Lifyzer\Server\Core\Container\Exception\Container as ContainerException;
use Lifyzer\Server\Core\Container\Exception\ContainerNotFound as ContainerNotFoundException;
use Lifyzer\Server\Core\Container\Exception\Provider as ProviderException;
use Lifyzer\Server\Core\Container\Provider\Providable;
use Lifyzer\Server\Core\Container\Provider\Twig as TwigContainer;
use Phake;
use PHPUnit\Framework\TestCase;
use Twig_Environment;

class ContainerTest extends TestCase
{
    private const PROVIDER_NAME = 'my_container';

    /** @var Container */
    private $container;

    protected function setUp(): void
    {
        $this->container = new Container();
    }

    public function testGetExistentContainer(): void
    {
        $this->container->register(TwigContainer::class, new TwigContainer());

        $this->assertInstanceOf(Twig_Environment::class, $this->container->get(TwigContainer::class));
    }

    public function testGetNonExistentContainer(): void
    {
        $this->expectException(ContainerNotFoundException::class);

        $this->container->get('idontexist');
    }

    public function testGetContainerThrowsException(): void
    {
        $this->expectException(ContainerException::class);

        $provider = Phake::mock(Providable::class);

        Phake::when($provider)->getService()->thenThrow(new ProviderException());

        $this->container->register(self::PROVIDER_NAME, $provider);
        $this->container->get(self::PROVIDER_NAME);
    }
}
