<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 * @link           https://lifyzer.com
 */

declare(strict_types=1);

namespace Lifyzer\Server\Core\Twig\Extension;

use Twig_Extension;
use Twig_SimpleFunction;

class Url extends Twig_Extension
{
    public function getFunctions(): array
    {
        return [
            new Twig_SimpleFunction(
                $this->getName(),
                [$this, 'getUrl']
            ),
        ];
    }

    public function getUrl(string $path = ''): string
    {
        return SITE_URL . $path;
    }

    public function getName(): string
    {
        return 'url';
    }
}
