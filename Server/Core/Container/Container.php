<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Server\Core\Container;

use Lifyzer\Server\Core\Container\Exception\Container as ContainerException;
use Lifyzer\Server\Core\Container\Exception\ContainerNotFound as ContainerNotFoundException;
use Lifyzer\Server\Core\Container\Exception\Provider as ProviderException;
use Lifyzer\Server\Core\Container\Provider\Providable;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /** @var Providable[] */
    private $provider = [];

    public function register(string $providerName, Providable $provider): void
    {
        $this->provider[$providerName] = $provider;
    }

    /**
     * @param string $id
     *
     * @return mixed
     *
     * @throws ContainerException
     */
    public function get($id)
    {
        if ($this->has($id)) {
            try {
                return $this->retrieve($id);
            } catch (ProviderException $except) {
                throw new ContainerException($id);
            }
        }

        throw new ContainerNotFoundException($id);
    }

    public function has($id): bool
    {
        return isset($this->provider[$id]);
    }

    private function retrieve(string $id)
    {
        return $this->provider[$id]->getService();
    }
}
