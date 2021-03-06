<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018-2020, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Server;

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

return simpleDispatcher(static function (RouteCollector $route) {
    $route->addRoute('GET', '/', 'Product@homepage');
    $route->addRoute('POST', '/search', 'Product@search');
    $route->addRoute('GET', '/results/{keywords:\w+}[/page-{page:\d+}]', 'Product@result');
    $route->addRoute('GET', '/product/{id:\d+}', 'Product@show');
    $route->addRoute('GET', '/posts', 'Post@homepage');
    $route->addRoute('GET', '/post/{post_name}', 'Post@post');
    $route->addRoute('GET', '/about', 'About@about');
});
