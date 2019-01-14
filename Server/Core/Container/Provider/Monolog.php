<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Server\Core\Container\Provider;

use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class Monolog implements Providable
{
    private const LOG_DIR = '/log/';
    private const LOG_EXT = '.log';

    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return LoggerInterface
     *
     * @throws Exception
     */
    public function getService(): LoggerInterface
    {
        $rootPath = dirname(__DIR__, 3);
        $streamHandler = new StreamHandler(
            $rootPath . self::LOG_DIR . $this->name . self::LOG_EXT,
            Logger::DEBUG
        );

        $log = new Logger($this->name);
        $log->pushHandler($streamHandler);

        return $log;
    }
}
