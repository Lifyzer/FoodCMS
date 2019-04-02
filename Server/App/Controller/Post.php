<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2019, Pierre-Henry Soria. All Rights Reserved.
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

    private const POSTS_DATA_PATH = '../../data/posts/en/';
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
        $this->view->display(self::HOMEPAGE_POSTS_VIEW_FILE, $this->getPostsList());
    }

    public function post(array $data): void
    {
        $postId = (int)$data['post_name'];

        if (is_file(self::POSTS_DATA_PATH . $postId . self::POST_FILE_EXT)) {
            $parsedown = new Parsedown();
            $postData = $parsedown->text(
                file_get_contents(self::POSTS_DATA_PATH . $postId . self::POST_FILE_EXT)
            );

            $this->view->display(self::POST_VIEW_FILE, $postData);
        } else {
            (new Error($this->container))->notFound();
        }
    }

    private function getPostsList(): array
    {
        return glob(self::POSTS_DATA_PATH);
    }
}
