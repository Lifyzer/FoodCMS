<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 * @link           https://lifyzer.com
 */

declare(strict_types=1);

namespace Lifyzer\Server\Core\Uri;

use FastRoute\Dispatcher;
use Lifyzer\Server\App\Controller\Error;
use Lifyzer\Server\Core\Container\Provider\Monolog;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use ReflectionException;
use ReflectionMethod;

class Router
{
    private const CONTROLLER_NAMESPACE = 'Lifyzer\Server\App\Controller\\';
    private const METHOD_DELIMITER = '@';

    /** @var ContainerInterface */
    private $container;

    /** @var Dispatcher */
    private $dispatcher;

    public function __construct(Dispatcher $dispatcher, ContainerInterface $container)
    {
        $this->dispatcher = $dispatcher;
        $this->container = $container;
    }

    public function dispatch(): void
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $uri = rawurldecode($uri);

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
            case Dispatcher::METHOD_NOT_ALLOWED:
                (new Error($this->container))->notFound();
                break;

            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $split = explode(self::METHOD_DELIMITER, $handler);
                $controller = self::CONTROLLER_NAMESPACE . $split[0];
                $method = $split[2];
                try {
                    $reflection = new ReflectionMethod($controller, $method);
                    if ($reflection->isPublic()) {
                        $reflection->invokeArgs(new $controller, $vars);
                    }
                } catch (ReflectionException $except) {
                    /** @var LoggerInterface $log */
                    $log = $this->container->get(Monolog::class);
                    $message = sprintf('Failed to get class/method: %s', $except->getMessage());
                    $log->info($message);

                    (new Error($this->container))->notFound();
                }
                break;
        }
    }
}
