<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2019-2020, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 * @link           https://lifyzer.com
 */

declare(strict_types=1);

namespace Lifyzer\Server\App\Controller;

use Psr\Container\ContainerInterface;

class About extends Base
{
    private const ABOUT_IT = 'about/about-it.twig';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function about(): void
    {
        $data = [
            'siteUrl' => SITE_URL,
            'siteName' => SITE_NAME,
            'pageName' => 'About The Tasty Lifyzer\'s Mission! 😋',
        ];

        $this->view->display(self::ABOUT_IT, $data);
    }
}
