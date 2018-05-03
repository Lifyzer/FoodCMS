<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 * @link           https://lifyzer.com
 */

declare(strict_types=1);

namespace Lifyzer\Server\App\Controller;

use Psr\Container\ContainerInterface;

class Product extends Base
{
    private const ADD_PRODUCT_FILENAME = 'product/add.twig';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function add(): void
    {
       $this->view->display(
           self::ADD_PRODUCT_FILENAME,
           [
               'siteName' => SITE_NAME,
               'pageName' => 'Add a Product'
           ]
       ) ;
    }
}
