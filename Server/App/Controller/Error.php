<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Server\App\Controller;

use Psr\Container\ContainerInterface;
use Teapot\StatusCode;

class Error extends Base
{
    private const NOT_FOUND_FILENAME = 'not-found.twig';
    private const NOT_FOUND_PAGE_NAME = 'Page Not Found';
    private const NOT_FOUND_MESSAGE = "The page doesn't exist";

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function notFound(): void
    {
        http_response_code(StatusCode::NOT_FOUND);

        $data = [
            'siteName' => SITE_NAME,
            'pageName' => self::NOT_FOUND_PAGE_NAME,
            'messages' => self::NOT_FOUND_MESSAGE
        ];

        $this->view->display(
            self::NOT_FOUND_FILENAME, $data);
    }
}
