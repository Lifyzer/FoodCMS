<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Server\Core\Container\Exception;

use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

class ContainerNotFound extends RuntimeException implements NotFoundExceptionInterface
{
    private const ERROR_MESSAGE = 'No entry was found for %s identifier.';

    public function __construct(string $id)
    {
        parent::__construct(
            sprintf(self::ERROR_MESSAGE, $id)
        );
    }
}
