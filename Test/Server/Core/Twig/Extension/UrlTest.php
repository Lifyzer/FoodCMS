<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2019-2020, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 * @link           https://lifyzer.com
 */

declare(strict_types=1);

namespace Lifyzer\Test\Server\Core\Twig\Extension;

use Lifyzer\Server\Core\Twig\Extension\Url as TwigUrlExtension;
use PHPUnit\Framework\TestCase;
use Twig_SimpleFunction;

class UrlTest extends TestCase
{
    /** @var TwigUrlExtension */
    private $twigUrlExtension;

    protected function setUp(): void
    {
        $this->twigUrlExtension = new TwigUrlExtension();
    }

    public function testGetName(): void
    {
        $this->assertSame($this->twigUrlExtension->getName(), 'url');
    }

    public function testReturnsCorrectFunction(): void
    {
        $functions = $this->twigUrlExtension->getFunctions();

        $this->assertCount(1, $functions);

        $function = current($functions);

        $this->assertInstanceOf(Twig_SimpleFunction::class, $function);
        $this->assertSame($this->twigUrlExtension->getName(), $function->getName());
    }

    public function testUrl(): void
    {
        $urlResult = $this->twigUrlExtension->getUrl('about');

        $this->assertSame(SITE_URL . 'about', $urlResult);
    }
}
