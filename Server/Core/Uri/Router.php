<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018-2020, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 * @link           https://lifyzer.com
 */

declare(strict_types=1);

namespace Lifyzer\Server\Core\Uri;

use FastRoute\Dispatcher;
use Lifyzer\Server\App\Controller\Error;
use Lifyzer\Server\Core\Container\Provider\HttpRequest;
use Lifyzer\Server\Core\Container\Provider\Monolog;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;

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
        /** @var Request $httpRequest */
        $httpRequest = $this->container->get(HttpRequest::class);
        $httpMethod = $httpRequest->getMethod();
        $queryString = (string)$httpRequest->getQueryString();
        $uri = $this->uriCleanup($queryString);

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
            case Dispatcher::METHOD_NOT_ALLOWED:
                (new Error($this->container))->notFound();
                break;

            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $arguments = $routeInfo[2];

                // PHP 7.1 new "[]" list() syntax :)
                [$controller, $method] = explode(self::METHOD_DELIMITER, $handler);
                $controller = self::CONTROLLER_NAMESPACE . $controller;
                try {
                    $reflector = new ReflectionMethod($controller, $method);
                    if ($reflector->isPublic()) {
                        $reflector->invokeArgs(new $controller($this->container), [$arguments]);
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

    private function uriCleanup(string $uri): string
    {
        $uri = str_replace('=', '', $uri);

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $uri = $this->addInitialSlashIfAbsent($uri);

        return rawurldecode($uri);
    }

    private function addInitialSlashIfAbsent(string $uri): string
    {
        return substr($uri, 0, 1) !== '/' ? '/' . $uri : $uri;
    }
}
