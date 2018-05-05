<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer;

use Dotenv\Dotenv;
use Lifyzer\Server\Core\Container\Container;
use Lifyzer\Server\Core\Container\Provider\Database as DatabaseContainer;
use Lifyzer\Server\Core\Container\Provider\HttpRequest as HttpRequestContainer;
use Lifyzer\Server\Core\Container\Provider\Monolog as MonologContainer;
use Lifyzer\Server\Core\Container\Provider\Twig as TwigContainer;
use Lifyzer\Server\Core\Debug;
use Lifyzer\Server\Core\Uri\Router;

require __DIR__ . '/Server/vendor/autoload.php';

(new Dotenv(__DIR__ . '/Server/config'))->load();
define('SITE_NAME', getenv('SITE_NAME'));
Debug::initializeMode();

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$container = new Container();
$container->register(TwigContainer::class, new TwigContainer());
$container->register(DatabaseContainer::class, new DatabaseContainer());
$container->register(HttpRequestContainer::class, new HttpRequestContainer());
$container->register(MonologContainer::class, new MonologContainer(getenv('LOGGING_CHANNEL')));

$dispatcher = require __DIR__ . '/Server/config/routes.php';
$router = new Router($dispatcher, $container);
$router->dispatch();
