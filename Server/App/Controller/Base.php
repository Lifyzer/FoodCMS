<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Server\App\Controller;

use Lifyzer\Server\Core\Container\Provider\HttpRequest;
use Lifyzer\Server\Core\Container\Provider\Monolog;
use Lifyzer\Server\Core\Container\Provider\Twig;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

abstract class Base
{
    /** @var Twig_Environment */
    protected $view;

    /** @var Monolog */
    protected $log;

    /** @var Request */
    protected $httpRequest;

    public function __construct(ContainerInterface $container)
    {
        $this->view = $container->get(Twig::class);
        $this->log = $container->get(Monolog::class);
        $this->httpRequest = $container->get(HttpRequest::class);
    }
}
