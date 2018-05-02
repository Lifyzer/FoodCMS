<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Server\Core\Container\Exception;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class Container extends Exception implements ContainerExceptionInterface
{
    public function __construct(string $id)
    {
        parent::__construct(
            sprintf('Error while retrieving the entry: %s.', $id)
        );
    }
}
