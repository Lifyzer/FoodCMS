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
    private const FILENAME = 'error.twig';

    private const NOT_FOUND_PAGE_NAME = 'Page Not Found';
    private const INTERNAL_ERROR_PAGE_NAME = 'Internal Error';

    private const NOT_FOUND_MESSAGE = "The page doesn't exist";
    private const INTERNAL_ERROR_MESSAGE = 'An Internal Error Occurred! Please try again later.';

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
            'message' => self::NOT_FOUND_MESSAGE
        ];

        $this->view->display(self::FILENAME, $data);
    }

    public function internalError(): void
    {
        http_response_code(StatusCode::INTERNAL_SERVER_ERROR);

        $data = [
            'siteName' => SITE_NAME,
            'pageName' => self::INTERNAL_ERROR_PAGE_NAME,
            'message' => self::INTERNAL_ERROR_MESSAGE
        ];

        $this->view->display(self::FILENAME, $data);
    }
}
