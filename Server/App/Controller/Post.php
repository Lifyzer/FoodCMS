<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2019-2020, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 * @link           https://lifyzer.com
 */

declare(strict_types=1);

namespace Lifyzer\Server\App\Controller;

use Parsedown;
use Psr\Container\ContainerInterface;

class Post extends Base
{
    private const HOMEPAGE_POSTS_VIEW_FILE = 'post/posts.twig';
    private const POST_VIEW_FILE = 'post/post.twig';

    private const POSTS_DATA_PATH = __DIR__ . '/../../data/posts/en/';
    private const POST_FILE_EXT = '.md';

    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->container = $container;
    }

    public function homepage(): void
    {
        $data = [
            'siteUrl' => SITE_URL,
            'siteName' => SITE_NAME,
            'pageName' => 'Healthy Posts. For Read Lovers!',
        ];

        $data['posts'] = $this->getPostsList();

        $this->view->display(self::HOMEPAGE_POSTS_VIEW_FILE, $data);
    }

    public function post(array $data): void
    {
        $postName = $data['post_name'];

        if ($this->isPostFound($postName)) {
            $parsedown = new Parsedown();
            $postData = $parsedown->text(
                file_get_contents(self::POSTS_DATA_PATH . $postName . self::POST_FILE_EXT)
            );

            $data = [
                'siteUrl' => SITE_URL,
                'siteName' => SITE_NAME,
                'pageName' => $postName,
                'keywords' => $postName,
                'content' => $postData
            ];

            $this->view->display(self::POST_VIEW_FILE, $data);
        } else {
            (new Error($this->container))->notFound();
        }
    }

    private function isPostFound(string $filename): bool
    {
        return is_file(self::POSTS_DATA_PATH . $filename . self::POST_FILE_EXT);
    }

    private function getPostsList(): array
    {
        $files = glob(
            sprintf('%s*%s', self::POSTS_DATA_PATH, self::POST_FILE_EXT)
        );

        return $this->cleanPostFilenames($files);
    }

    private function cleanPostFilenames(array $files): array
    {
        return array_map(
            static function (string $file) {
                return str_replace(
                    [
                        self::POSTS_DATA_PATH,
                        self::POST_FILE_EXT,
                    ],
                    '',
                    $file
                );
            }, $files);
    }
}
