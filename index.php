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
use Lifyzer\Server\Core\Debug;
use Lifyzer\Server\Core\Uri\Router;

require __DIR__ . '/Server/vendor/autoload.php';

(new Dotenv(__DIR__ . '/Server/config'))->load();
define("SITE_NAME", getenv('SITE_NAME'));
Debug::initializeMode();

$container = new Container();
$container->setContainers();
$dispatcher = require  __DIR__ . '/Server/config/routes.php';
$router = new Router($dispatcher, $container);
$router->dispatch();
