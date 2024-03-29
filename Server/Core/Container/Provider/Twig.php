<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018-2020, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Server\Core\Container\Provider;

use Twig_Environment;
use Twig_Loader_Filesystem;

class Twig implements Providable
{
    private const VIEW_FOLDER = '/App/View/';
    private const CACHE_FOLDER = '/cache/';

    public function getService(): Twig_Environment
    {
        $rootPath = dirname(__DIR__, 3);

        $loader = new Twig_Loader_Filesystem($rootPath . self::VIEW_FOLDER);

        return new Twig_Environment(
            $loader,
            $this->getOptions($rootPath)
        );
    }

    private function getOptions(string $rootPath): array
    {
        $cacheStatus = filter_var($_ENV['CACHE'], FILTER_VALIDATE_BOOLEAN);

        return [
            'cache' => $cacheStatus ? $rootPath . self::CACHE_FOLDER : false,
            'debug' => DEBUG_MODE
        ];
    }
}
