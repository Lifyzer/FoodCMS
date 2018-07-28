<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Test\Server\Core\Uri;

use FastRoute\Dispatcher;
use Lifyzer\Server\Core\Container\Provider\HttpRequest;
use Lifyzer\Server\Core\Container\Provider\Monolog;
use Lifyzer\Server\Core\Container\Provider\Twig;
use Lifyzer\Server\Core\Uri\Router;
use Phake;
use Phake_IMock;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

class RouterTest extends TestCase
{
    private const HTTP_METHOD = 'get';
    private const ROOT_QUERY_STRING = '/';
    private const INEXISTENT_QUERY_STRING = '/inexistent-page/';

    /** @var ContainerInterface|Phake_IMock */
    private $container;

    /** @var Router|Phake_IMock */
    private $router;

    /** @var Request|Phake_IMock */
    private $request;

    /** @var LoggerInterface|Phake_IMock */
    private $monolog;

    /** @var Twig_Environment|Phake_IMock */
    private $view;

    /** @var Dispatcher|Phake_IMock */
    private $dispatcher;

    protected function setUp(): void
    {
        $this->container = Phake::mock(ContainerInterface::class);
        $this->dispatcher = Phake::mock(Dispatcher::class);

        $this->request = Phake::mock(Request::class);
        $this->monolog = Phake::mock(LoggerInterface::class);
        $this->view = Phake::mock(Twig_Environment::class);

        Phake::when($this->container)->get(HttpRequest::class)->thenReturn($this->request);
        Phake::when($this->container)->get(Monolog::class)->thenReturn($this->monolog);
        Phake::when($this->container)->get(Twig::class)->thenReturn($this->view);

        $this->router = new Router($this->dispatcher, $this->container);
    }

    public function testDispatcherNotFoundPage(): void
    {
        Phake::when($this->request)->getMethod()->thenReturn(self::HTTP_METHOD);
        Phake::when($this->request)->getQueryString()->thenReturn(self::INEXISTENT_QUERY_STRING);

        $this->router->dispatch();

        Phake::verify($this->dispatcher)->dispatch(self::HTTP_METHOD, self::INEXISTENT_QUERY_STRING);

        $this->assertRequestMethods();

        Phake::verify($this->view)->display('error.twig', [
            'siteName' => 'SITE_NAME',
            'pageName' => 'Page Not Found',
            'message' => 'The page doesn\'t exist',
            'siteUrl' => 'SITE_URL'
        ]);
    }

    public function testDispatcherFoundWithIndex(): void
    {
        Phake::when($this->request)->getMethod()->thenReturn(self::HTTP_METHOD);
        Phake::when($this->request)->getQueryString()->thenReturn(self::ROOT_QUERY_STRING);

        $this->router->dispatch();

        Phake::verify($this->dispatcher)->dispatch(self::HTTP_METHOD, self::ROOT_QUERY_STRING);

        $this->assertRequestMethods();

        Phake::verify($this->view)->display(Phake::anyParameters());
    }

    private function assertRequestMethods(): void
    {
        Phake::verify($this->request)->getMethod();
        Phake::verify($this->request)->getQueryString();
    }
}
