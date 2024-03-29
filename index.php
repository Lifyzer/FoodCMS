<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018-2020, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer;

use Dotenv\Dotenv;
use Lifyzer\Server\Core\Container\Container;
use Lifyzer\Server\Core\Container\Provider\Database as DatabaseContainer;
use Lifyzer\Server\Core\Container\Provider\HttpRequest as HttpRequestContainer;
use Lifyzer\Server\Core\Container\Provider\Monolog as MonologContainer;
use Lifyzer\Server\Core\Container\Provider\SwiftMailer as SwiftMailerContainer;
use Lifyzer\Server\Core\Container\Provider\Twig as TwigContainer;
use Lifyzer\Server\Core\Debug;
use Lifyzer\Server\Core\Uri\Router;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRun;

require __DIR__ . '/Server/vendor/autoload.php';

$whoops = new WhoopsRun;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

$requiredEnvFields = [
    'SITE_URL',
    'SITE_NAME',
    'ADMIN_EMAIL',
    'DB_HOST',
    'DB_USER',
    'DB_PWD',
    'DB_NAME',
    'LOGGING_CHANNEL'
];

$env = Dotenv::createImmutable(__DIR__ . '/Server/config');
$env->load();
$env->required($requiredEnvFields)->notEmpty();

define('SITE_NAME', $_ENV['SITE_NAME']);
define('SITE_URL', $_ENV['SITE_URL']);
Debug::initializeMode();

$container = new Container();
$container->register(TwigContainer::class, new TwigContainer());
$container->register(DatabaseContainer::class, new DatabaseContainer());
$container->register(HttpRequestContainer::class, new HttpRequestContainer());
$container->register(SwiftMailerContainer::class, new SwiftMailerContainer());
$container->register(MonologContainer::class, new MonologContainer($_ENV['LOGGING_CHANNEL']));

$dispatcher = require __DIR__ . '/Server/config/routes.php';
$router = new Router($dispatcher, $container);
$router->dispatch();
